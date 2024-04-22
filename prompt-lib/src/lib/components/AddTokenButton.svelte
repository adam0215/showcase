<script lang="ts">
	import Icon from '@iconify/svelte'
	import DynamicTextToken from '$lib/components/DynamicTextToken.svelte'
	import { fly } from 'svelte/transition'
	import { quintInOut } from 'svelte/easing'
	import { placedTokenStore } from '../../routes/compositor/edit/placedTokenStore'
	import { cloneDeep } from 'lodash'

	export let index: PropAddTokenButtonIndex

	let showButton = false
	let showInput = false
	let assignedName = ''
	let currInputValue = ''

	let placedTokens: PropDynamicPromptCardTokens
	placedTokenStore.subscribe((currObj) => (placedTokens = currObj))

	function handleSaveToken(name: string, startChar: number) {
		if (name.length < 1) return

		const placedTokenStoreCopy = cloneDeep(placedTokens) ?? {}
		let existingKey = placedTokenStoreCopy[name]

		for (const t in placedTokenStoreCopy) {
			// If this index already is named in the object, and that name is not the same
			if (placedTokenStoreCopy[t] === startChar && t !== name) {
				// Create new key and remove the old one
				placedTokenStoreCopy[name] = startChar
				delete placedTokenStoreCopy[t]
				// If this index already named and the name is the same
			} else if (placedTokenStoreCopy[t] === startChar && t === name) return
		}

		if (existingKey) {
			// If key / record exist, modify the existing key
			existingKey = startChar
		} else {
			// If key / record doesnt exist, create on in the object
			placedTokenStoreCopy[name] = startChar
		}

		placedTokenStore.update((currObj) => placedTokenStoreCopy)
		console.log(placedTokens)
	}

	function handleRemoveToken(name: string, startChar: number) {
		if (name.length < 1) return

		const placedTokenStoreCopy = cloneDeep(placedTokens) ?? {}
		let existingKey = placedTokenStoreCopy[name]

		if (existingKey) {
			// Remove based on name
			delete placedTokenStoreCopy[name]
			// Reset local name to reset state
			assignedName = ''
		} else {
			// Remove based on startChar / index value
			for (const t in placedTokenStoreCopy) {
				if (placedTokenStoreCopy[t] === startChar) {
					delete placedTokenStoreCopy[t]
					// Reset local name to reset state
					assignedName = ''
				}
			}
		}

		placedTokenStore.update((currObj) => placedTokenStoreCopy)
		console.log(placedTokens)
	}

	function handleSetAssignedText(keyEvent: KeyboardEvent) {
		if (keyEvent.key === 'Enter') {
			assignedName = currInputValue
			handleSaveToken(assignedName, index)
			showInput = false
			showButton = false
		}
	}
</script>

{#if !showButton}
	<span
		class="h-[1em] w-2 cursor-pointer"
		tabindex="0"
		role="button"
		on:mouseenter={() => (showButton = true)}
	/>
{/if}

{#if showButton || assignedName.length > 0}
	<div class="group relative" title={assignedName.length > 0 ? assignedName : 'Add Dynamic Token'}>
		<!-- TOUCH AREA -->
		<span
			class="peer absolute left-[2px] top-0 z-10 aspect-square w-[1.5em] cursor-pointer outline-none"
			tabindex="0"
			role="button"
			on:keypress
			on:click
			on:click={() => (showInput = !showInput)}
			on:keypress={() => (showInput = !showInput)}
			on:mouseleave={() => (showInput ? null : (showButton = false))}
		/>
		<!-- --- -->
		{#if assignedName.length > 0}
			<!-- DYNAMIC TEXT TOKEN -->
			<DynamicTextToken
				clickable={true}
				removable={true}
				onRemove={() => handleRemoveToken(assignedName, index)}
			/>
		{:else}
			<!-- ADD BUTTON -->
			<span
				class="relative mx-2 inline-block aspect-square h-[1em] rounded-full bg-emerald-100 align-middle ring-emerald-500"
				tabindex="0"
				role="button"
			>
				{#if showInput}
					<input
						on:keypress={(e) => handleSetAssignedText(e)}
						bind:value={currInputValue}
						transition:fly={{ duration: 300, y: 32, opacity: 0, easing: quintInOut }}
						type="text"
						class="absolute bottom-8 left-0 rounded-r rounded-t bg-emerald-50 px-2 py-1 text-center text-sm text-emerald-700 shadow-md shadow-emerald-500/30 outline-none ring-2 ring-emerald-500 placeholder:text-emerald-500"
						placeholder="Token name"
					/>
				{/if}
				<Icon icon="ic:round-plus" class="text-emerald-500" />
			</span>
		{/if}
	</div>
{/if}
