function getHumanBytes(size) {
	const units = ['B', 'KB', 'MB', 'GB'];
	var unit = 0;
	while( size > 1000 && units[unit + 1] ) {
		unit++;
		size /= 1000;
	}
	return number_format(size, 1) + ' ' + units[unit];
}

class UploadFile {
	
	static STATE = {
		'STARTING': 'STARTING',
		'UPLOADING': 'UPLOADING',
		'FINISHED': 'FINISHED',
		'ERROR': 'ERROR',
	};
	
	static knownFormats = {
		'audio': 'fas fa-music',
		'image': 'fas fa-image',
		'text': 'fas fa-file-alt',
		'video': 'fas fa-film',
		'application/pdf': 'fas fa-file-pdf',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fas fa-file-word',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'fas fa-file-excel'
	};
	
	constructor(file) {
		// console.log('New upload file from', file);
		this.file = file;
		this.state = UploadFile.STATE.STARTING;
		this.data = null;
		this.$component = $('<li class="list-group-item upload-file d-flex justify-content-between align-items-center"></li>');
	}
	
	setUploader(uploader) {
		this.uploader = uploader;
	}
	
	commit() {
		if( !this.uploader ) {
			throw new Error('No available uploader to commit file');
		}
		this.uploader.commit([this]);
	}
	
	setState(state, data) {
		this.state = state;
		if( data !== undefined ) {
			this.data = data;
		}
		this.render();
	};
	
	getLabel() {
		return this.file.name;
	};
	
	getHumanSize() {
		return getHumanBytes(this.file.size);
	};
	
	renderIcon() {
		var type = this.file.type;
		var iconClass = 'fas fa-file';
		if( UploadFile.knownFormats[type] ) {
			// By complete type : application/pdf
			iconClass = UploadFile.knownFormats[type];
		} else {
			type = type.split('/');
			if( UploadFile.knownFormats[type[0]] ) {
				// First type part only : image (in image/jpeg)
				iconClass = UploadFile.knownFormats[type[0]];
			}
			// Else unknown type, use generic file icon
		}
		
		return $('<i class=""></i>').addClass(iconClass);
	};
	
	renderBadge() {
		// var badgeClass = 'badge-success';
		var badgeClass, badgeText, badgeTitle;
		switch( this.state ) {
			case UploadFile.STATE.STARTING:
				badgeClass = 'badge-info';
				badgeText = 'Starting...';
				break;
			case UploadFile.STATE.UPLOADING:
				badgeClass = 'badge-primary';
				badgeText = this.data;
				break;
			case UploadFile.STATE.FINISHED:
				badgeClass = 'badge-success';
				badgeText = 'Done';
				break;
			case UploadFile.STATE.ERROR:
				badgeClass = 'badge-danger';
				badgeText = 'Error';
				if( this.data ) {
					var message = typeof (this.data.message) === 'string' ? this.data.message : this.data.message.message;
					badgeTitle = this.data.status.capitalize() + ' : ' + message.substring(0, 40);
				}
				break;
		}
		return $('<span class="badge badge-pill">Done</span>').addClass(badgeClass).text(badgeText).attr('title', badgeTitle);
	};
	
	renderRetryButton() {
		var self = this;
		return $('<button class="btn btn-outline-secondary btn-sm ml-2"><i class="fas fa-redo"></i></button>')
			.click(function () {
				$(this).remove();
				self.commit();
			});
	}
	
	renderDownloadButton() {
		return $('<a class="btn btn-outline-success btn-sm ml-2"><i class="fas fa-download"></i></a>')
			.attr('href', this.data.link);
	}
	
	render() {
		var $name = $('<div></div>').append(this.renderIcon()).append(' ' + this.getLabel()).append($('<span class="text-muted ml-2"></span>').text('(' + this.getHumanSize() + ')'));
		var $state = $('<span></span>').append(this.renderBadge());
		if( this.state === UploadFile.STATE.ERROR ) {
			$state.append(this.renderRetryButton());
		} else if( this.state === UploadFile.STATE.FINISHED && this.data && this.data.link ) {
			$state.append(this.renderDownloadButton());
		}
		return this.$component.empty().append($name).append($state);
	};
}

class UploadBucket {
	
	static BATCH_MAX_SIZE = 1;
	
	constructor(uploader) {
		this.uploader = uploader;
		this.files = [];
		
		this.$list = $('.upload-file-list');
		this.$listWrapper = $('.upload-file-list-wrapper');
		this.$counter = this.$listWrapper.find('.counter');
	}
	
	addFileList(fileList) {
		// console.log('fileList', fileList);
		var pendingFiles = [];
		var currentBatch = 0;
		for( var i = 0; i < fileList.length; i++ ) {
			if( !pendingFiles[currentBatch] ) {
				pendingFiles[currentBatch] = [];
			} else if( pendingFiles[currentBatch].length >= UploadBucket.BATCH_MAX_SIZE ) {
				currentBatch++;
				pendingFiles[currentBatch] = [];
			}
			var uploadFile = new UploadFile(fileList.item(i));
			this.addFile(uploadFile);
			pendingFiles[currentBatch].push(uploadFile);
		}
		pendingFiles.forEach(files => {
			// console.log('files', files);
			this.uploader.commit(files);
		});
	}
	
	addFile(uploadFile) {
		uploadFile.setUploader(this.uploader);
		this.files.push(uploadFile);
		this.$list.append(uploadFile.render());
		this.$listWrapper.show();
		this.$counter.text(this.files.length);
	};
}

class UploadHandler {
	
	constructor(uploader) {
		this.uploadBucket = null;
		this.$dropZone = null;
	}
	
	commit(files) {
		var self = this;
		var form = new FormData(form);
		files.forEach(uploadFile => {
			form.append('file[]', uploadFile.file);
			uploadFile.setState(UploadFile.STATE.STARTING);
		});
		var startDate = new Date();
		$.ajax({
			type: 'POST',
			url: '/user/files.json',
			data: form,
			dataType: 'json',
			processData: false,
			contentType: false,
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				//Upload progress
				xhr.upload.addEventListener("progress", function (event) {
					var progress = Math.round(event.loaded * 100 / event.total) + '%';
					var delay = (new Date()).getTime() - startDate.getTime();
					if( delay > 3 ) {
						progress += ' / ' + getHumanBytes(Math.round(event.loaded * 1000 / delay)) + '/s';
					}
					console.log('Upload', delay, progress, event);
					self.setBatchState(files, UploadFile.STATE.UPLOADING, progress);
				}, false);
				
				//Download progress
				// xhr.addEventListener("progress", function (event) {
				// 	var percentComplete = event.loaded / event.total;
				// 	console.log('Download', Math.round((percentComplete * 100) / 2) + "%", event);
				// }, false);
				return xhr;
			}
		}).done(function (data) {
			console.log('Upload done, got', data);
			if( !isArray(data) ) {
				self.setBatchState(files, UploadFile.STATE.FINISHED);
			} else {
				data.forEach(file => {
					const uploadFile = files.find(uploadFile => uploadFile.getLabel() === file.name);
					if( file.status === 'ok' ) {
						uploadFile.setState(UploadFile.STATE.FINISHED, file.file);
					} else {
						uploadFile.setState(UploadFile.STATE.ERROR, {status: 'InputError', message: file.message});
					}
				});
			}
		}).fail(function (response, textStatus, errorThrown) {
			// console.log('Upload failed, got', response, textStatus, errorThrown);
			// console.log('response', response.statusCode(), response.getAllResponseHeaders());
			self.setBatchState(files, UploadFile.STATE.ERROR, {status: textStatus, message: errorThrown});
		});
	}
	
	setBatchState(files, state, data) {
		files.forEach(uploadFile => {
			uploadFile.setState(state, data);
		});
	}
	
	start() {
		var self = this;
		if( this.isAdvancedUploader ) {
			self.$dropZone = $('.uploader');
			self.$dropZone.addClass('uploader-handled');
			// Bind events
			self.$dropZone
				.on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
					e.preventDefault();
					e.stopPropagation();
				})
				.on('dragover dragenter', function () {
					self.$dropZone.addClass('dragging-over');
				})
				.on('dragleave dragend drop', function (event) {
					// dragleave is called hovering drop zone's children
					var isChild = !!self.$dropZone.find(event.relatedTarget).length;
					if( !isChild ) {
						self.$dropZone.removeClass('dragging-over');
					}
				})
				.on('drop', function (e) {
					self.uploadBucket.addFileList(e.originalEvent.dataTransfer.files);
				});
			
			this.uploadBucket = new UploadBucket(this);
		}
	}
	
	isAdvancedUploader() {
		var div = document.createElement('div');
		return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
	}
}

$(function () {
	var uploader = new UploadHandler();
	uploader.start();
});
