import { Configuration, OpenAIApi } from 'openai'
import { OPENAI_API_KEY } from '$env/static/private'
import type { ActionsExport } from './$types.js'

const configuration = new Configuration({
	apiKey: OPENAI_API_KEY
})

const openai = new OpenAIApi(configuration)

export const actions: any = {
	callOpenAiThree: async ({ request }: ActionsExport) => {
		const formData = await request.formData()
		const userPrompt = formData.get('user-prompt') ?? ''
		const formattedUserPrompt = userPrompt.toString().trim()

		if (!userPrompt) return { success: false, chatResponse: null } as RemoteChatResponse

		const chatCompletion = await openai.createChatCompletion({
			model: 'gpt-3.5-turbo',
			messages: [
				{
					role: 'system',
					content:
						'You are a helpful assistant that helps the user to the best of your ability. If you dont know how to answer something, say I dont know.'
				},
				{ role: 'user', content: formattedUserPrompt }
			]
		})

		return {
			success: true,
			chatResponse: chatCompletion.data.choices[0].message as AiChatMessage
		}
	}
}

export const load = async ({ url }) => {
	return {
		paramPromptId: url.searchParams.get('pid'),
		paramPromptType: url.searchParams.get('t'),
		shortPrompt: url.searchParams.get('p')
	}
}
