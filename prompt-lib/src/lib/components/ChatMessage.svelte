<script lang="ts">
	export let content: PropChatMessageContent
	export let author: PropChatMessageAuthor = null
	export let authorType: PropChatMessageAuthorType

	if (!author) {
		switch (authorType) {
			case 'user':
				author = 'You'
				break
			case 'assistant':
				author = 'Assistant'
				break
			case 'system':
				author = 'System'
				break
			default:
				author = 'Unknown'
				break
		}
	}
</script>

<div class="flex w-full max-w-prose items-start gap-4 p-4">
	<div class="flex w-full flex-col">
		<p class="mb-4">
			{content}
		</p>
		<div class="flex w-full items-baseline justify-between gap-2">
			<span class="text-sm font-medium text-gray-700">{author}</span>
			<!-- DIVIDER -->
			<span class="h-[2px] w-full rounded-full bg-gray-300" />
		</div>
	</div>
</div>
