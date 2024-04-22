import { insertDynamicPrompt } from '$lib/server/db.server'
import { getDynamicPromptById } from '$lib/server/db.server'
import { redirect } from '@sveltejs/kit'

export const load = async ({ params }: any) => {
	const promptData = (await getDynamicPromptById(parseInt(params.id))) ?? {}
	const promptTags = promptData.map((res) => res.tags?.text ?? null)

	if (promptData.length < 1) throw redirect(308, '/use-dynamic/1')

	return {
		promptId: params.id,
		promptTitle: promptData[0].dynamic_prompts.title,
		promptContent: promptData[0].dynamic_prompts.prompt,
		promptTokens: promptData[0].dynamic_prompts.tokens as { [key: string]: number },
		promptTags: promptTags
	}
}

/* export const actions = {
	insertDynamicPrompt: async (event) => {
		const res = await insertDynamicPrompt()
		console.log(res)
	}
} */
