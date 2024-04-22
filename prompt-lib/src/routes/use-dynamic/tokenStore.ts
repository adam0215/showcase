import { writable, type Writable } from 'svelte/store'

export const assignedTokenStore: Writable<PropTokenAssignedTokenValues> = writable({})
