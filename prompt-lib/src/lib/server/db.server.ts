import { drizzle } from 'drizzle-orm/planetscale-serverless'
import { connect } from '@planetscale/database'
import { dynamicPromptTags, dynamicPrompts, staticPromptTags, staticPrompts, tags } from './schema'
import { DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD } from '$env/static/private'
import { eq } from 'drizzle-orm'

import * as schema from './schema'

// create the connection
const connection = connect({
	host: DATABASE_HOST,
	username: DATABASE_USERNAME,
	password: DATABASE_PASSWORD
})

const db = drizzle(connection, { schema })

export async function getAllDynamicPrompts(limit: number = 10) {
	const res = await db.query.dynamicPrompts.findMany({
		with: {
			tags: {
				columns: {
					tagId: true
				},
				with: {
					tag: {
						columns: {
							text: true
						}
					}
				}
			}
		},
		limit: limit
	})

	return res
}

export async function getAllStaticPrompts(limit: number = 10) {
	const res = await db.query.staticPrompts.findMany({
		with: {
			tags: {
				columns: {
					tagId: true
				},
				with: {
					tag: {
						columns: {
							text: true
						}
					}
				}
			}
		},
		limit: limit
	})

	return res
}

export async function getDynamicPromptById(id: number) {
	const res = await db
		.select()
		.from(dynamicPrompts)
		.where(eq(dynamicPrompts.id, id))
		.leftJoin(dynamicPromptTags, eq(dynamicPrompts.id, dynamicPromptTags.promptId))
		.leftJoin(tags, eq(dynamicPromptTags.tagId, tags.id))

	return res
}

export async function insertDynamicPrompt() {
	const res = await db.insert(dynamicPrompts).values({
		prompt: 'Non sunt laborum magna commodo.',
		title: 'First DB Prompt',
		tokens: { concept: 13 }
	})

	return res
}

export async function getTagByText(text: string) {
	const res = await db.selectDistinct().from(tags).where(eq(tags.text, text))

	if (res.length < 1) return null

	return res[0]
}

export async function createTag(text: string) {
	const res = await db.insert(tags).values({ text: text })

	return res
}

export async function insertStaticPrompt(title: string, content: string, promptTags: string[]) {
	const res = await db.transaction(async (tx) => {
		const prompt = await tx.insert(staticPrompts).values({
			title: title,
			prompt: content
		})

		// Array of tag-id:s to include
		const tagIds: any[] = []

		// Loop through tags and check if they exist, if they dont, create a record in tags-table
		for (const tag of promptTags) {
			const existingTag = await tx.selectDistinct().from(tags).where(eq(tags.text, tag))

			if (existingTag.length < 1) {
				const newTag = await tx.insert(tags).values({ text: tag })
				tagIds.push(newTag.insertId)
			} else {
				tagIds.push(String(existingTag[0].id))
			}
		}

		// Array of objects representing rows in static_prompt_tags ready for direct insertion below
		const promptTagPairs = tagIds.map((id) => {
			return { promptId: parseInt(prompt.insertId), tagId: parseInt(id) }
		})

		const promptTagsJunction = await tx.insert(staticPromptTags).values(promptTagPairs)

		return {
			promptId: prompt.insertId,
			promptTitle: title,
			promptContent: content,
			promptTags: promptTags
		}
	})

	return res
}
