<script lang="ts">
	import AddTokenButton from './AddTokenButton.svelte'
	import DynamicTextToken from './DynamicTextToken.svelte'

	export let promptWords: any[]
	export let tokenInsertion = true
	export let placeholder = ''
	export let name: string | null = null

	let currText = ''
	let promptIsFocused = false
	let promptParagraphEl: HTMLParagraphElement

	// Function for one-way binding the textContent of the paragraph
	function handleUpdate(e: Event) {
		const target = e.target as HTMLInputElement

		// Tokens removed from text
		const cleanValue =
			target.textContent
				?.split(' ')
				.reduce((prev, curr) => (typeof curr === 'string' ? `${prev} ${curr}` : ''))
				.trim() ?? ''

		// Set current text variable to updated string value
		currText = cleanValue
	}

	function handleFocus(focused: boolean) {
		// Set component variable
		promptIsFocused = focused

		// Timeout required for caret to appear on focus
		setTimeout(() => {
			if (focused) promptParagraphEl.focus()
			if (!focused) promptParagraphEl.blur()
		}, 0)
	}
</script>

<textarea {name} id={name} class="absolute hidden" bind:value={currText} />

<p
	class="flex min-h-[2rem] max-w-prose flex-wrap items-center text-2xl caret-emerald-500 outline-none"
	contenteditable="true"
	on:keyup={(e) => handleUpdate(e)}
	on:keydown={(e) => handleUpdate(e)}
	on:focus={() => handleFocus(true)}
	on:blur={() => handleFocus(false)}
	bind:this={promptParagraphEl}
>
	{#if tokenInsertion}
		<!-- USER ABLE TO INSERT DYNAMIC TOKENS -->
		{#each promptWords as word, i}
			{#if /* REMOVE FIRST SPACE */ i !== 0}
				<AddTokenButton index={i} />
			{/if}
			{#if typeof word.component !== typeof DynamicTextToken}
				<span class="inline-block">{word}</span>
			{/if}
		{/each}
	{:else}
		<!-- USER UNABLE TO INSERT DYNAMIC TOKENS -->
		{promptWords.join(' ')}
	{/if}

	{#if placeholder.length > 1 && !promptIsFocused && currText.length < 1}
		<span class="text-gray-500">{placeholder}</span>
	{/if}
</p>
