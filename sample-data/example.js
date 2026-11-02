/**
 * Sample JavaScript file for the editor preview.
 */

(function () {
	'use strict';

	const MAX_ITEMS = 5;
	const items = [];

	/**
	 * Delay a function until calls stop for `wait` milliseconds.
	 *
	 * @param {Function} fn   Function to call.
	 * @param {number}   wait Delay in milliseconds.
	 * @return {Function} Debounced function.
	 */
	function debounce(fn, wait = 200) {
		let timer = null;

		return (...args) => {
			clearTimeout(timer);
			timer = setTimeout(() => fn.apply(this, args), wait);
		};
	}

	class TodoList {
		constructor(element) {
			this.element = element;
			this.filter = '';
		}

		add(title) {
			if (!title.trim() || items.length >= MAX_ITEMS) {
				return false;
			}

			items.push({ id: Date.now(), title, done: false });
			this.render();
			return true;
		}

		toggle(id) {
			const item = items.find((entry) => entry.id === id);
			if (item) {
				item.done = !item.done;
				this.render();
			}
		}

		render() {
			const visible = items.filter((item) =>
				item.title.toLowerCase().includes(this.filter.toLowerCase())
			);

			this.element.innerHTML = visible
				.map((item) => `<li class="${item.done ? 'done' : ''}">${item.title}</li>`)
				.join('');
		}
	}

	async function loadInitialItems(url) {
		try {
			const response = await fetch(url);
			const data = await response.json();
			return Array.isArray(data) ? data.slice(0, MAX_ITEMS) : [];
		} catch (error) {
			console.error('Could not load items:', error);
			return [];
		}
	}

	document.addEventListener('DOMContentLoaded', async () => {
		const list = new TodoList(document.querySelector('#todo-list'));
		const search = document.querySelector('#todo-search');

		search?.addEventListener('input', debounce((event) => {
			list.filter = event.target.value;
			list.render();
		}, 150));

		(await loadInitialItems('/api/todos.json')).forEach((item) => list.add(item.title));
	});
})();
