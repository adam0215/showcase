<script lang="ts">
	import Icon from '@iconify/svelte'
	import SmallTag from './SmallTag.svelte'
	import DynamicTokenPara from './DynamicTokenPara.svelte'

	export let title: PropPromptCardTitle
	export let content: PropPromptCardContent
	export let tokens: PropDynamicPromptCardTokens
	export let tags: PropPromptCardTags
</script>

<div class="flex max-w-md flex-col justify-between gap-12 rounded-xl p-8 ring-2 ring-emerald-500">
	<div class="flex flex-col gap-4">
		<span class="flex w-full items-center justify-between">
			<h3 class="text-4xl font-semibold text-emerald-600">{title}</h3>
			<Icon icon="solar:atom-linear" width="40" class="text-emerald-300" />
		</span>
		<div class="flex gap-2">
			{#each tags as tag}
				{#if tag}
					<SmallTag text={tag} />
				{/if}
			{/each}
		</div>
	</div>
	<p class="text-xl text-gray-700">
		<DynamicTokenPara {content} {tokens} clickableTextTokens={true} editableTokens={true} />
	</p>
</div>
