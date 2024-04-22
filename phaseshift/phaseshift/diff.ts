const jsdom = require('jsdom')
const { JSDOM } = jsdom

export function compareTemplates(
	template1: string,
	template2: string,
	expressionsToCheck: { [key: string]: string[] }
): [string, string][] {
	console.time('TIME COMPARING TEMPLATES')

	const t1DOM = new JSDOM(template1).window.document
	const t2DOM = new JSDOM(template2).window.document

	const differingElements: [string, string][] = []

	for (const id in expressionsToCheck) {
		const t1Element = t1DOM.querySelector(`[data-psid="${id}"]`)
		const t2Element = t2DOM.querySelector(`[data-psid="${id}"]`)

		if (t1Element.outerHTML !== t2Element.outerHTML) {
			differingElements.push([t2Element.outerHTML, id])
		}
	}

	console.timeEnd('TIME COMPARING TEMPLATES')

	return differingElements
}
