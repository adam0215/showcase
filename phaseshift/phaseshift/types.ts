export type AppConfig = {
	port: number
	procedures?: { [key: string]: (...args: any) => void }
	state?: { [key: string]: any }
}

export type InteractionMessage = {
	// CODE 20: INTERACTION
	code: number
	interaction?: string
	procedure?: string
	target?: string
}

export type TemplateCacheObject = { [key: string]: { [key: string]: string[] } }
