<script lang="ts">
	import Icon from '@iconify/svelte'
	import PrimaryButton from './PrimaryButton.svelte'
	import SmallTag from './SmallTag.svelte'
	import DynamicTokenPara from './DynamicTokenPara.svelte'
	import { goto } from '$app/navigation'

	export let id: PropPromptCardId
	export let title: PropPromptCardTitle
	export let content: PropPromptCardContent
	export let tokens: PropDynamicPromptCardTokens
	export let tags: PropPromptCardTags
	export let clickableTextTokens: PropClickableTokens
</script>

<div class="flex max-w-sm flex-col justify-between gap-6 rounded-xl p-4 ring-2 ring-emerald-500">
	<div class="flex flex-col gap-2">
		<span class="flex w-full items-start justify-between">
			<h3 class="text-xl font-semibold text-emerald-600">{title}</h3>
			<Icon icon="solar:atom-linear" width="24" class="text-emerald-300" />
		</span>
		<div class="flex gap-2">
			{#each tags as tag}
				{#if tag}
					<SmallTag text={tag} />
				{/if}
			{/each}
		</div>
	</div>
	<DynamicTokenPara {content} {tokens} {clickableTextTokens} />
	<div class="flex gap-4">
		<PrimaryButton text="Customize & Use" on:click={() => goto(`/use-dynamic/${id}`)} />
	</div>
</div>
