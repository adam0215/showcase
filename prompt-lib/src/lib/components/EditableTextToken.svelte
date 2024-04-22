<script lang="ts">
	import Icon from '@iconify/svelte'
	import { quintInOut } from 'svelte/easing'
	import { fly } from 'svelte/transition'
	import { assignedTokenStore } from '../../routes/use-dynamic/tokenStore'
	import cloneDeep from 'lodash/cloneDeep'

	let showInput = false
	let currInputValue = ''

	export let tokenName: PropTokenName
	export let assignedText: PropTokenAssignedText

	let assignedTokenValues: PropTokenAssignedTokenValues

	assignedTokenStore.subscribe((arr) => {
		assignedTokenValues = arr
	})

	const defaultStyle =
		'relative mx-1 inline-flex cursor-pointer items-center rounded-full bg-emerald-100 px-4 py-0 align-middle text-emerald-500 transition-all duration-300 hover:bg-emerald-300 hover:text-emerald-100 hover:shadow-lg hover:shadow-emerald-500/30'

	const assignedTextStyle =
		'relative mx-1 inline-flex cursor-pointer text-sm items-center rounded bg-emerald-100 px-4 py-0 align-middle text-emerald-500 transition-all duration-300 hover:bg-emerald-300 hover:text-emerald-100 hover:shadow-lg hover:shadow-emerald-500/30'

	function handleSetAssignedText(keyEvent: KeyboardEvent) {
		if (keyEvent.key === 'Enter') {
			assignedText = currInputValue

			// Lodash DeepClone
			const tokenStoreCopy = cloneDeep(assignedTokenValues)

			// Hide input again
			for (const t in tokenStoreCopy) {
				if (t === tokenName) {
					const tStartChar: number = tokenStoreCopy[t][0]

					tokenStoreCopy[t] = [tStartChar, assignedText]

					assignedTokenStore.update((currArr) => tokenStoreCopy)
				}
			}

			showInput = false
		}
	}
</script>

<div class={assignedText?.length > 0 ? assignedTextStyle : defaultStyle}>
	{#if showInput}
		<input
			bind:value={currInputValue}
			on:keypress={(e) => handleSetAssignedText(e)}
			transition:fly={{ duration: 300, y: 32, opacity: 0, easing: quintInOut }}
			type="text"
			class="absolute bottom-8 left-0 rounded-r rounded-t bg-emerald-50 px-2 py-1 text-center text-sm text-emerald-700 shadow-md shadow-emerald-500/30 ring-2 ring-emerald-500 placeholder:text-emerald-500 focus-within:outline-none"
			placeholder={tokenName}
		/>
	{/if}

	{#if assignedText?.length > 0}
		<!-- CLICK HANDLER -->
		<div
			tabindex="0"
			on:click={() => (showInput = !showInput)}
			on:keypress={() => (showInput = !showInput)}
			role="button"
			class="absolute left-0 h-full w-full"
		/>
		<!-- --- -->
		<span>{assignedText}</span>
	{:else}
		<!-- CLICK HANDLER -->
		<div
			tabindex="0"
			on:click={() => (showInput = !showInput)}
			on:keypress={() => (showInput = !showInput)}
			role="button"
			class="absolute left-0 h-full w-full"
		/>
		<!-- --- -->
		<Icon icon="solar:menu-dots-linear" width="20" />
	{/if}
</div>
