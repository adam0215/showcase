<script lang="ts">
	import { goto } from '$app/navigation'
	import EditableDynamicPrompt from '$lib/components/EditableDynamicPrompt.svelte'
	import PrimaryButton from '$lib/components/PrimaryButton.svelte'
	import { assignedTokenStore } from '../tokenStore.js'

	export let data

	let assignedTokenValues: { [key: string]: [number, string] }
	assignedTokenStore.subscribe((currArr) => (assignedTokenValues = currArr))

	function generatePrompt(tokenValues: { [key: string]: [number, string] }) {
		const splitPrompt = data.promptContent.split('')
		// Add assigned token values to string
		for (const t in tokenValues) {
			const assignedStartChar = tokenValues[t][0]
			const assignedText = tokenValues[t][1]
			splitPrompt.splice(assignedStartChar, 0, ` ${assignedText} `)
		}

		const generatedPrompt: string = splitPrompt.join('')

		return generatedPrompt
	}

	function handleSubmit() {
		const generatedPrompt = generatePrompt(assignedTokenValues)
		goto(`/chat?pid=${data.promptId}&t=dynamic&p=${generatedPrompt}`)
	}
</script>

<div class="flex flex-col gap-6">
	<h1 class="">
		<span class="text-gray-500">Use</span>
		{data.promptTitle}
	</h1>
	<EditableDynamicPrompt
		title={data.promptTitle}
		content={data.promptContent}
		tokens={data.promptTokens}
		tags={data.promptTags}
	/>
	<PrimaryButton
		text="Send Prompt"
		iconifyIcon="solar:square-arrow-right-linear"
		on:click={handleSubmit}
	/>
</div>
