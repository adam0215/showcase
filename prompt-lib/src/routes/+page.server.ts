import { getAllDynamicPrompts, getAllStaticPrompts } from '$lib/server/db.server.js'

export const load = async () => {
	const staticPromptData = (await getAllStaticPrompts()) ?? [{}]
	const dynamicPromptData = (await getAllDynamicPrompts()) ?? [{}]

	if (staticPromptData.length < 1 && dynamicPromptData.length < 1)
		return { staticPrompts: null, dynamicPrompts: null }

	return {
		staticPrompts: staticPromptData,
		dynamicPrompts: dynamicPromptData
	}
}
