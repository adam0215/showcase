import { psWebServer } from './server'
import type { AppConfig } from './types'

export function app(app: AppConfig) {
	const webServer = Bun.serve(psWebServer(app) as any)

	console.log(
		`Web server and Websocket listening on ${webServer.hostname}:${webServer.port}`
	)
}
