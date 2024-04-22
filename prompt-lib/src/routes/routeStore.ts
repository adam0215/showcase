import { writable, type Writable } from 'svelte/store'

export const currentRouteStore: Writable<{ routeName: string | null; routePath: string | null }> =
	writable({
		routeName: null,
		routePath: null
	})
