export const load = ({ url }) => {
	return {
		paramPromptId: url.searchParams.get('pid'),
		paramPromptTitle: url.searchParams.get('pt'),
		paramPromptType: url.searchParams.get('t'),
		shortPrompt: url.searchParams.get('p')
	}
}
