<script lang="ts">
	import Icon from '@iconify/svelte'
	import SecondaryButton from './SecondaryButton.svelte'
	import PrimaryButton from './PrimaryButton.svelte'
	import SmallTag from './SmallTag.svelte'
	import { goto } from '$app/navigation'

	export let id: PropPromptCardId
	export let title: PropPromptCardTitle
	export let content: PropPromptCardContent
	export let tags: PropPromptCardTags

	/* If prompt is up to a 1000 characters save it here. This means that it can be 
	sent in the url-parameters. A url can in theory be around 2000 characters, 
	1000 chars is a very safe breakpoint. */
	const shortPrompt: string | null = content.length <= 1000 ? content : null
	const shortPromptStringParameter: string | null = shortPrompt ? `&p=${shortPrompt}` : null
</script>

<div
	class="flex min-w-[256px] max-w-sm flex-col justify-between gap-6 rounded-xl p-4 ring-2 ring-emerald-500"
>
	<div class="flex flex-col gap-2">
		<span class="flex w-full items-start justify-between">
			<h3 class="text-xl font-semibold text-emerald-600">{title}</h3>
			<Icon icon="solar:clipboard-text-linear" width="24" class="text-emerald-300" />
		</span>
		<div class="flex gap-2">
			{#each tags as tag}
				{#if tag}
					<SmallTag text={tag} />
				{/if}
			{/each}
		</div>
	</div>
	<p class="text-gray-700">
		{content}
	</p>
	<div class="flex gap-4">
		<PrimaryButton
			text="Use Now"
			on:click={() =>
				goto(
					`/chat?pid=${id}&t=static${shortPromptStringParameter ? shortPromptStringParameter : '0'}`
				)}
		/>
		<SecondaryButton
			on:click={() =>
				goto(
					`/compositor/edit?pid=${id}&t=static&pt=${title}${
						shortPromptStringParameter ? shortPromptStringParameter : '0'
					}`
				)}
			text="Edit & Use"
		/>
	</div>
</div>
