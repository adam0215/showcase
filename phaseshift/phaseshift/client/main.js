import { bindListeners, replaceNodeOuter } from './dom.js'
import { Message } from './messages.js'

const entryPoint = document.querySelector('[data-entrypoint]')

// Create a new WebSocket instance
const socket = new WebSocket('ws://localhost:3000/ws')

// Add event listeners for different WebSocket events
socket.addEventListener('open', () => {
	console.log('WebSocket connection established')

	socket.send(Message.handshake())
})

socket.addEventListener('message', (event) => {
	/**
	 * @type {{code: number, html: string, message: string, target: string}}
	 */
	const jsonMessage = JSON.parse(event.data.trim())

	/* console.log(jsonMessage) */

	if (jsonMessage.html) {
		const nodeToReplace = document.querySelector(
			`[data-psid="${jsonMessage.target}"]`
		)

		/* console.log(jsonMessage) */

		if (jsonMessage.target !== 'entry' && jsonMessage.target) {
			replaceNodeOuter(nodeToReplace, jsonMessage.html)
		} else {
			replaceNodeOuter(entryPoint, jsonMessage.html)
		}

		// Bind subsequent new elements that came after first page load
		bindListeners(socket)
	}
})

socket.addEventListener('error', (error) => {
	console.error('WebSocket error:', error)
})

socket.addEventListener('close', (event) => {
	console.log('WebSocket connection closed:', event.code, event.reason)
})

bindListeners(socket)
