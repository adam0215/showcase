export class Message {
	/**
	 * @param {'click'} interaction
	 * @param {string} procedure
	 * @param {string | null} target
	 *
	 */
	static interaction(interaction, procedure, target) {
		const message = JSON.stringify({
			// CODE 20: INTERACTION
			code: 20,
			interaction: interaction,
			procedure: procedure,
			target: target,
		})

		return message
	}

	/**
	 *
	 */
	static handshake() {
		const message = JSON.stringify({
			// CODE 69: HANDSHAKE
			code: 69,
		})

		return message
	}
}
