/* PROMPT CARDS */
type PropPromptCardId = number
type PropPromptCardTitle = string
type PropPromptCardContent = string
type PropPromptCardTags = (string | null)[]

type PropDynamicPromptCardTokens = { [key: string]: number } | null
/* --- */

/* SMALL TAG */
type PropSmallTagText = string
/* --- */

/* SIDE NAV */
type CurrentRouteObj = { routeName: string | null; routePath: string | null }
/* --- */

/* PRIMARY BUTTON & SECONDARY BUTTON */
type PropButtonText = string
type PropButtonIcon = string | null
type HTMLButtonTypeOptions = 'button' | 'reset' | 'submit' | null | undefined
/* --- */

/* TEXT TOKEN */
type PropTokenName = string
type PropTokenAssignedText = string
type PropTokenAssignedTokenValues = { [key: string]: [number, string] }
/* --- */

/* DYNAMIC TOKEN PARAGRAPH */
type PropDynamicParaContent = string
type PropClickableTokens = boolean
type PropEditableTokens = boolean
type PropRemovableTokens = boolean
/* --- */

/* CHAT */
type PresetPrompt = string | null
/* --- */

/* AI CHAT MESSAGE */
type PropChatMessageContent = string
type PropChatMessageAuthor = string | null
type PropChatMessageAuthorType = 'user' | 'assistant' | 'system'

type RemoteChatResponse = {
	success: true | false
	chatResponse: AiChatMessage | null
}

type AiChatMessage = {
	role: 'assistant' | 'user' | 'system'
	content: string
}
/* --- */

/* ADD TOKEN BUTTON */
type PropAddTokenButtonIndex = number
/* --- */

/* PROMPT LIST */
interface ListData {
	dynamicPrompts: {
		id: number
		title: string
		prompt: string
		tags: { tag: { text: string } }[]
		tokens: { [key: string]: number }
	}[]
	staticPrompts: {
		id: number
		title: string
		prompt: string
		tags: { tag: { text: string } }[]
	}[]
}
/* --- */
