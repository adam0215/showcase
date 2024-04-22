import { getTagByText, insertStaticPrompt } from '$lib/server/db.server.js'

export const actions = {
	createPrompt: async ({ request }) => {
		const formData = await request.formData()

		const promptTitle = formData.get('prompt-title') as string
		const promptContent = formData.get('prompt-content') as string

		if (!promptTitle || promptTitle.length < 1) return { success: false }
		if (!promptContent || promptContent.length < 1) return { success: false }

		const dbRecord = await insertStaticPrompt(promptTitle, promptContent, ['STATIC'])

		if (dbRecord) {
			return { success: true, ...dbRecord }
		}
	}
}
