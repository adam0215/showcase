export class Message {
	static clientUpdate(message: string, html: string, target: string | null) {
		const _message = JSON.stringify({
			code: 2,
			message: message,
			html: html,
			target: target,
		})

		return _message
	}

	static handshake() {
		const message = JSON.stringify({
			// CODE 69: HANDSHAKE
			code: 69,
		})

		return message
	}
}
