<script lang="ts">
	import { enhance } from '$app/forms'
	import { goto } from '$app/navigation'
	import EditableDynamicPara from '$lib/components/EditableDynamicPara.svelte'
	import PrimaryButton from '$lib/components/PrimaryButton.svelte'
	import SecondaryButton from '$lib/components/SecondaryButton.svelte'

	let promptWords: any[] = ['']

	export let form

	$: if (form?.success) {
		goto(`/chat?pid=${form.promptId}&t=static&p=${form.promptContent}`)
	}
</script>

<div class="flex h-full flex-col items-center">
	<div class="mb-12 flex w-full items-center justify-between">
		<h1 class="mb-0">Compositor - Create</h1>
	</div>
	<div
		class="flex h-full w-full flex-col items-center justify-center overflow-scroll rounded-lg bg-gray-100 p-4"
	>
		<form
			class="flex w-full max-w-prose flex-col items-start gap-12"
			action="create?/createPrompt"
			method="post"
			use:enhance
		>
			<div class="flex w-full flex-col gap-6">
				<input
					name="prompt-title"
					type="text"
					class="bg-transparent text-2xl font-bold caret-emerald-500 outline-none placeholder:text-gray-500"
					placeholder="Name your prompt..."
					autocomplete="off"
				/>
				<EditableDynamicPara
					promptWords={[' ']}
					placeholder="Write the prompt..."
					tokenInsertion={true}
					name="prompt-content"
				/>
			</div>
			<div class="flex gap-4">
				<PrimaryButton
					attributes={{ name: 'send-use-now', type: 'submit' }}
					text="Save and use now"
				/>
				<SecondaryButton
					attributes={{ name: 'send-leave', type: 'submit' }}
					text="Save and leave"
				/>
			</div>
		</form>
	</div>
</div>
