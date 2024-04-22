import { writable, type Writable } from 'svelte/store'

export const placedTokenStore: Writable<PropDynamicPromptCardTokens> = writable({})
