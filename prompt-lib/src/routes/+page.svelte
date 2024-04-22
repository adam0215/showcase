<script lang="ts">
	import DynamicPromptCard from '$lib/components/DynamicPromptCard.svelte'
	import StaticPromptCard from '$lib/components/StaticPromptCard.svelte'
	import Icon from '@iconify/svelte'

	export let data: ListData
</script>

<svelte:head>
	<title>Library - PROMPTLIB.</title>
</svelte:head>


<div class="mb-12 flex w-full items-center justify-between">
	<h1 class="mb-0">Prompt Library</h1>
	<span class="group flex items-center gap-4">
		<p class="duration-250 opacity-0 transition-all group-hover:opacity-100">Create new prompt</p>
		<button title="Create new prompt">
			<Icon icon="solar:add-square-bold" width={40} class="text-emerald-500" />
		</button>
	</span>
</div>
<div class="grid max-w-max grid-cols-1 justify-start gap-6 md:grid-cols-2 lg:grid-cols-3">
	{#each data.staticPrompts ?? [] as { id, title, prompt, tags }}
		<StaticPromptCard
			{id}
			{title}
			content={prompt}
			tags={tags.map((tag) => tag.tag?.text ?? null)}
		/>
	{/each}
	{#each data.dynamicPrompts ?? [] as { id, title, prompt, tokens, tags }}
		<DynamicPromptCard
			{id}
			{title}
			content={prompt}
			clickableTextTokens={false}
			tags={tags.map((tag) => tag.tag?.text ?? null)}
			{tokens}
		/>
	{/each}
</div>
