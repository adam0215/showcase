import staticPlugin from '@elysiajs/static'
import { Elysia } from 'elysia'
import { Renderer } from './template'
import { Message } from './messages'
import type { AppConfig, InteractionMessage } from './types'
import { compareTemplates } from './diff'

const ROUTE_FILES = {
	'/': 'phaseshift/client/index.html',
	'/main.js': 'phaseshift/client/main.js',
	'/messages.js': 'phaseshift/client/messages.js',
	'/dom.js': 'phaseshift/client/dom.js',
}

export function psWebServer(app: AppConfig) {
	const webServer = new Elysia()
	const renderer = new Renderer()

	webServer
		.use(staticPlugin())
		.get('/', async () => {
			const html = Bun.file(ROUTE_FILES['/'])

			return new Response(html, {
				headers: { 'Content-Type': 'text/html' },
			})
		})
		.get('/main.js', async () => Bun.file(ROUTE_FILES['/main.js']))
		.get('/messages.js', async () => Bun.file(ROUTE_FILES['/messages.js']))
		.get('/dom.js', async () => Bun.file(ROUTE_FILES['/dom.js']))
		.ws('/ws', {
			perMessageDeflate: true,
			async open(ws) {
				renderer.setWebSocket(ws)

				const render = await renderer.render(
					'phaseshift/templates/counter.html',
					app.state
				)

				render.send((rendered) => Message.clientUpdate('', rendered, null))
			},
			async message(ws, message) {
				const jsonMessage = message as InteractionMessage

				/* console.log(`(message)
                code: ${jsonMessage.code}
                procedure: ${jsonMessage.procedure}
                target: ${jsonMessage.target}
                `) */

				switch (jsonMessage.code) {
					// HANDSHAKE
					case 69: {
						ws.send(Message.handshake())
						break
					}
					// INTERACTION
					case 20: {
						if (!app.procedures) return

						if (jsonMessage.interaction === 'click') {
							const previousStateRender = (
								await renderer.render(
									'phaseshift/templates/counter.html',
									app.state
								)
							).get()

							// Run procedure, update state and so on
							app.procedures[jsonMessage.procedure!]()

							const newRender = (
								await renderer.render(
									'phaseshift/templates/counter.html',
									app.state
								)
							).get()

							const elementExpressionCache =
								await renderer.getElementExpressionCache()

							const differingElements = compareTemplates(
								previousStateRender,
								newRender,
								elementExpressionCache['phaseshift/templates/counter.html']
							)

							if (differingElements.length !== 0) {
								for (const [elHtml, elId] of differingElements) {
									console.log('sending patch', elId)
									ws.send(Message.clientUpdate('', elHtml, elId))
								}
							}
						}

						break
					}
				}
			},
		})
		.listen(app.port)

	return webServer
}
