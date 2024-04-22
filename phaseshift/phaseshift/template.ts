const jsdom = require('jsdom')
const { JSDOM } = jsdom
import { nanoid } from 'nanoid'
import type { TemplateCacheObject } from './types'

export class Renderer {
	private rendered = ''
	private ws: any | null = null

	constructor(ws?: any) {
		this.ws = ws ?? null
	}

	private async renderTemplateFile(
		html: string,
		state: { [key: string]: string | number }
	): Promise<[string, { [key: string]: string[] }]> {
		console.time('TIME TO PARSE TEMPLATE')

		const { document } = new JSDOM(html).window

		const allTextNodes: any[] = []

		const walker = document.createTreeWalker(document.documentElement, 4)

		while (walker.nextNode()) {
			allTextNodes.push(walker.currentNode)
		}

		const expressionsUsedInFile: { [key: string]: string[] } = {}

		for (const node of allTextNodes) {
			const parentNode = node.parentNode

			const parentIndexToParent = Array.from(
				parentNode.parentNode.childNodes
			).indexOf(parentNode)

			const nodeText = node.nodeValue ?? ''

			if (!parentNode) continue

			let parentInnerHTML = parentNode.innerHTML

			const expressionPattern = /{{\s*([^{}\s]+)\s*}}/g

			const expressions = nodeText?.match(expressionPattern) ?? null

			// Found expressions
			if (expressions) {
				const nodeIdHash = Bun.hash(
					`${parentIndexToParent}${parentNode.nodeName}`
				)

				if (!parentNode.getAttribute('data-psid'))
					parentNode.setAttribute('data-psid', nodeIdHash /* nanoid(8) */)

				const capturedExpressions = expressions.map((match: string) => {
					// Extract variable
					const variable = match.match(/\b([^{}\s]+)\b/)![1].trim()

					// Add value in place of expression
					parentInnerHTML = parentInnerHTML.replace(match, state[variable])
					parentNode.innerHTML = parentInnerHTML

					return variable
				})

				// Add expressions to id in "used expressions" object
				expressionsUsedInFile[parentNode.getAttribute('data-psid')] =
					capturedExpressions
			}
		}

		const mutatedHTML = document.documentElement.outerHTML

		console.timeEnd('TIME TO PARSE TEMPLATE')

		return [mutatedHTML, expressionsUsedInFile]
	}

	private async cacheExpressionsUsed(
		templateName: string,
		expressionsInFile: { [key: string]: string[] },
		cachePath: string
	) {
		let templateCacheFile = Bun.file(cachePath)

		if (!(await templateCacheFile.exists())) {
			await Bun.write(cachePath, '{}')
			templateCacheFile = Bun.file(cachePath)
		}

		const templateCacheJson: TemplateCacheObject =
			(await templateCacheFile.json()) ?? {}

		for (const elId in expressionsInFile) {
			templateCacheJson[templateName] = expressionsInFile
		}

		await Bun.write(templateCacheFile, JSON.stringify(templateCacheJson))
	}

	public async getElementExpressionCache(): Promise<{
		[key: string]: { [key: string]: string[] }
	}> {
		const cache = await Bun.file('template.cache.json').json()

		return cache
	}

	private async template(
		templateFileName: string,
		state: { [key: string]: string | number },
		cache?: string
	): Promise<[string, { [key: string]: string[] }]> {
		const template = await Bun.file(templateFileName).text()

		const [parsed, expressionsInFile] = await this.renderTemplateFile(
			template,
			state
		)

		if (cache) {
			this.cacheExpressionsUsed(templateFileName, expressionsInFile, cache)
		}

		return [parsed, expressionsInFile]
	}

	public async render(
		templatePath: string,
		values: { [key: string]: any } = {}
	) {
		this.rendered = (
			await this.template(templatePath, values, 'template.cache.json')
		)[0]

		return this
	}

	public setWebSocket(ws: any) {
		this.ws = ws
	}

	public get() {
		return this.rendered
	}

	public send(senderFn: (rendered: string) => string) {
		if (!this.ws) return

		this.ws?.send(senderFn(this.rendered))
	}
}
