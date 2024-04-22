import { Message } from './messages.js'

const ATTRIBUTES = ['it-click']
export function selectAllInteractiveElements() {
	return document.querySelectorAll(`[${ATTRIBUTES.join(', ')}]`)
}

/**
 *
 * @param {Element | null} root
 * @param {Element | string | null} replacement
 */
export function replaceNodeOuter(root, replacement) {
	// @ts-ignore
	root.outerHTML = replacement
}

/**
 * @description Bind respective event listener to each element marked as interactive via interactive attributes
 * @param {WebSocket} ws
 */
export function bindListeners(ws) {
	/* console.log('binding elements') */
	const elements = selectAllInteractiveElements()

	for (const el of elements) {
		// Mark to element with an unique id
		// @ts-ignore
		el['uuid'] = crypto.randomUUID()

		if (el.getAttribute('it-click')) {
			el.onclick = () => {
				// @ts-ignore
				/* console.log('CLICKED!', el['uuid']) */
				console.log('clicked!')

				ws.send(
					Message.interaction(
						'click',
						/** @type {string} */ (el.getAttribute('it-click')),
						// @ts-ignore
						el['uuid']
					)
				)
			}
		}
	}
}
