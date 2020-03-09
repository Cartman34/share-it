function resolveCondition(condition, data) {
	const conditionParts = condition.split(' ');
	var invert = conditionParts[0] === 'not';
	var property = conditionParts.length > 1 ? conditionParts[1] : conditionParts[0];
	return invert ^ data[property];
}

function renderTemplateString(string, data) {
	// Clean contents
	let template = string.replace(/>\s+</ig, '><');
	
	// Resolve values in attributes
	template = template.replace(/\{\{ ([^\}]+) \}\}/ig, (all, property) => {
		return data[property];
	});
	
	return template;
}

function renderTemplateElement($template, data, prefix) {
	// Resolve conditional displays
	$template.find('[data-if]').each((index, $element) => {
		$element = $($element);
		if( !resolveCondition($element.data('if'), data) ) {
			$element.remove();
		}
	});
	// Fix image loading preventing
	$template.find('[data-src]').each((index, $element) => {
		$element = $($element);
		$element.attr('src', $element.data('src'));
		$element.removeAttr('data-src').data('src', null);
	});
	// Resolve values in content
	$template.fill(prefix, data);
}

function renderTemplate(template, data, prefix) {
	if( isJquery(template) ) {
		template = template.is('template') ? $(template.prop('content').children).prop('outerHTML') : template.html();
	}
	template = renderTemplateString(template, data);
	
	// Create jquery object preventing jquery to preload images
	var $item = $(template.replace('src=', 'data-src='));
	renderTemplateElement($item, data, prefix);
	return $item;
}

class FileEditor {
	constructor(prefix) {
		this.prefix = prefix;
		this.$editDialog = $('#DialogFileEdit');
		this.$editSubmit = this.$editDialog.find('.action-edit-submit');
		this.$editSystemState = this.$editDialog.find('.system-state');
		this.$deleteDialog = $('#DialogFileDelete');
		this.$deleteSubmit = this.$deleteDialog.find('.action-delete-submit');
		this.$deleteSystemState = this.$deleteDialog.find('.system-state');
	}
	
	emptyEditState() {
		this.$editSystemState.hide().empty();
		return this;
	}
	
	emptyDeleteState() {
		this.$deleteSystemState.hide().empty();
		return this;
	}
	
	addEditAlert(contents, state) {
		this.emptyEditState();
		this.$editSystemState.show().append($('<div class="alert" role="alert"></div>')
			.addClass('alert-' + state)
			.html(contents));
		return this;
	}
	
	addDeleteAlert(contents, state) {
		this.emptyDeleteState();
		this.$deleteSystemState.show().append($('<div class="alert" role="alert"></div>')
			.addClass('alert-' + state)
			.html(contents));
		return this;
	}
	
	normalizeForm(form) {
		var object = {};
		for( let [key, value] of form.entries() ) {
			object[key] = value;
		}
		return object;
	}
	
	requestUpdate(id, form) {
		this.addEditAlert('Saving...', 'success');
		this.$editSubmit.prop('disabled', true);
		return $.ajax({
			type: 'PUT',
			url: '/api/file/' + id,
			data: this.normalizeForm(form),
			dataType: 'json'
		}).done(() => {
			this.$editDialog.modal('hide');
		}).fail(() => {
			this.addEditAlert('An error occurred, we were unable to request update', 'danger');
		}).always(() => {
			this.$editSubmit.prop('disabled', false);
		});
	}
	
	requestDelete(id) {
		this.addDeleteAlert('Removing...', 'success');
		this.$deleteSubmit.prop('disabled', true);
		return $.ajax({
			type: 'DELETE',
			url: '/api/file/' + id,
			dataType: 'json'
		}).done(() => {
			this.$deleteDialog.modal('hide');
		}).fail((e, status, message) => {
			console.log('Fail', e, status, message);
			this.addDeleteAlert('An error occurred, we were unable to request delete', 'danger');
		}).always(() => {
			this.$deleteSubmit.prop('disabled', false);
		});
	}
	
	edit(item) {
		this.$editSystemState.hide().empty();
		this.$editDialog.fill(this.prefix, item.data);
		this.$editDialog.modal('show');
		
		this.$editSubmit.prop('disabled', false).off('click').click(() => {
			var form = new FormData(this.$editDialog.find('form')[0]);
			this.requestUpdate(item.data.id, form)
				.done(data => item.update(data));
		});
	}
	
	delete(item) {
		this.$deleteSystemState.hide().empty();
		this.$deleteDialog.fill(this.prefix, item.data);
		this.$deleteDialog.modal('show');
		
		this.$deleteSubmit.prop('disabled', false).off('click').click(() => {
			this.requestDelete(item.data.id)
				.done(() => item.remove());
		});
	}
}

class ListItem {
	
	constructor(list, data) {
		this.list = list;
		this.data = data;
		this.render();
	}
	
	remove() {
		$(this.$item).remove();
	}
	
	update(data) {
		this.data = data;
		this.render();
	}
	
	render() {
		let $previous = this.$item;
		this.$item = renderTemplate(this.list.$model, this.data, this.list.prefix);
		this.$item.data('item', this);
		// Bind events
		this.$item.find('.action-update').click(() => this.list.editor.edit(this));
		this.$item.find('.action-delete').click(() => this.list.editor.delete(this));
		// Put in list if it was before
		if( $previous ) {
			$previous.after(this.$item).remove();
			this.$item.addClass('just-updated');
		}
	}
	
}

class FileList {
	
	constructor() {
		this.prefix = 'file_';
		this.$items = [];
		this.$list = $('#ListFile');
		this.$model = $('#ModelFileListItem').detach();
		this.$loading = this.$list.find('.loading');
		this.editor = new FileEditor(this.prefix);
		
		this.load();
	}
	
	add(data) {
		let item = new ListItem(this, data);
		this.$items.push(item);
		this.$list.append(item.$item);
	}
	
	empty() {
		this.$items.forEach(item => {
			item.remove();
		});
		this.$items = [];
	}
	
	load() {
		this.empty();
		this.$loading.show();
		$.getJSON('/api/me/file', (data) => {
			this.empty();
			this.$loading.hide();
			Object.values(data).forEach(file => this.add(file));
		});
	}
	
	requestRefresh() {
		if( this.refreshTimeout ) {
			clearTimeout(this.refreshTimeout);
		}
		this.refreshTimeout = setTimeout(() => {
			this.load();
			this.refreshTimeout = null;
		}, 100);
	}
	
}

$(function () {
	userFileList = new FileList();
});
