<script lang="ts">
	import { goto } from '$app/navigation'
	import EditableDynamicPara from '$lib/components/EditableDynamicPara.svelte'
	import PrimaryButton from '$lib/components/PrimaryButton.svelte'
	import SecondaryButton from '$lib/components/SecondaryButton.svelte'
	import { redirect } from '@sveltejs/kit'

	export let data

	let presetTitle = ''
	let presetPrompt = ''

	if (data.paramPromptTitle && data.shortPrompt) {
		presetPrompt = data.shortPrompt
		presetTitle = data.paramPromptTitle
	}

	let promptWords: any[] = presetPrompt.split(' ')
</script>

<div class="flex h-full flex-col items-center">
	<div class="mb-12 flex w-full items-center justify-between">
		<h1 class="mb-0">Compositor - Edit</h1>
	</div>
	<div
		class="flex h-full w-full flex-col items-center justify-center overflow-scroll rounded-lg bg-gray-100 p-4"
	>
		<div class="flex max-w-prose flex-col items-start gap-12">
			<div class="flex flex-col gap-6">
				<h2 class="text-2xl font-bold">{presetTitle}</h2>
				<EditableDynamicPara
					{promptWords}
					tokenInsertion={data.paramPromptType === 'dynamic' ? true : false}
				/>
			</div>
			<div class="flex gap-4">
				<PrimaryButton text="Save and use now" />
				<SecondaryButton text="Save and leave" />
			</div>
		</div>
	</div>
</div>
