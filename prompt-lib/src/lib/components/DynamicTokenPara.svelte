<script lang="ts">
	import { onMount } from 'svelte'
	import DynamicTextToken from './DynamicTextToken.svelte'
	import EditableTextToken from './EditableTextToken.svelte'
	import { assignedTokenStore } from '../../routes/use-dynamic/tokenStore'

	export let content: PropDynamicParaContent
	export let tokens: PropDynamicPromptCardTokens
	export let clickableTextTokens: PropClickableTokens
	export let editableTokens: PropEditableTokens = false

	const contentAsArr: any[] /* VAFAN SKA MAN HA FÖR TYPE 😭 */ = content.split(' ')

	onMount(() => {
		if (tokens) {
			// Generate token objects for store
			const newAssignedTokenObj: { [key: string]: [number, string] } = {}

			for (const t in tokens) {
				newAssignedTokenObj[t] = [tokens[t], '']
			}

			assignedTokenStore.update(() => newAssignedTokenObj)
		}
	})

	if (tokens) {
		for (const t in tokens) {
			const tStartChar: number = tokens[t]

			if (tStartChar < content.length) {
				contentAsArr.splice(tokens[t], 0, {
					clickable: clickableTextTokens,
					tokenName: t ?? null,
					component: editableTokens ? EditableTextToken : DynamicTextToken
				})
			}
		}
	}
</script>

<p>
	{#each contentAsArr as char}
		{#if typeof char.component === typeof DynamicTextToken}
			<svelte:component
				this={char.component}
				clickable={char.clickable}
				tokenName={char.tokenName}
			/>
		{:else}
			{' ' + char + ' '}
		{/if}
	{/each}
</p>
