!function ($, window, document, _undefined) {
	"use strict";

	// ################################## NESTABLE HANDLER ###########################################

	XF.ListSorter = XF.Element.newHandler({

		options:
		{
			dragParent: null,
			dragHandle: null,
			undraggable: '.is-undraggable'
		},

		drake: null,

		init: function ()
		{
			if (this.options.dragParent)
			{
				$(window).on('listSorterDuplication', XF.proxy(this, 'drakeSetup'));
			}

			this.drakeSetup();
		},

		drakeSetup: function()
		{
			if (this.drake)
			{
				this.drake.destroy();
			}

			var dragContainer = this.options.dragParent
				? this.$target.find(this.options.dragParent).get()
				: [this.$target.get(0)];

			this.drake = dragula(
				dragContainer,
				{
					moves: XF.proxy(this, 'isMoveable'),
					accepts: XF.proxy(this, 'isValidTarget'), direction: 'grid'
				}
			);
		},

		isMoveable: function (el, source, handle, sibling)
		{
			var handleIs = this.options.dragHandle,
				undraggableIs = this.options.undraggable;

			if (handleIs)
			{
				if (!$(handle).closest(handleIs).length)
				{
					return false;
				}
			}
			if (undraggableIs)
			{
				if ($(el).closest(undraggableIs).length)
				{
					return false;
				}
			}

			return true;
		},

		isValidTarget: function (el, target, source, sibling)
		{
			var $sibling;

			if (!sibling)
			{
				$sibling = this.$target.children().last();

			}
			else
			{
				$sibling = $(sibling).prev();
			}

			while ($sibling.length)
			{
				if ($sibling.is('.js-blockDragafter'))
				{
					return false;
				}

				$sibling = $sibling.prev();
			}

			return true;
		}
	});

	XF.Element.register('list-sorter', 'XF.ListSorter');
}
(jQuery, window, document);