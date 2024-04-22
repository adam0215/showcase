import { relations } from 'drizzle-orm'
import { mysqlTable, serial, varchar, text, json, int, primaryKey } from 'drizzle-orm/mysql-core'

export const staticPrompts = mysqlTable('static_prompts', {
	id: serial('id').primaryKey(),
	title: varchar('title', { length: 256 }).notNull(),
	prompt: text('prompt').notNull()
})

export const dynamicPrompts = mysqlTable('dynamic_prompts', {
	id: serial('id').primaryKey(),
	title: varchar('title', { length: 256 }).notNull(),
	prompt: text('prompt').notNull(),
	tokens: json('tokens')
})

export const tags = mysqlTable('tags', {
	id: serial('id').primaryKey(),
	text: varchar('text', { length: 20 })
})

export const staticPromptTags = mysqlTable(
	'static_prompt_tags',
	{
		promptId: int('prompt_id').references(() => staticPrompts.id),
		tagId: int('tag_id').references(() => tags.id)
	},
	(table) => {
		return {
			id: primaryKey(table.promptId, table.tagId)
		}
	}
)

export const dynamicPromptTags = mysqlTable(
	'dynamic_prompt_tags',
	{
		promptId: int('prompt_id').references(() => dynamicPrompts.id),
		tagId: int('tag_id').references(() => tags.id)
	},
	(table) => {
		return {
			id: primaryKey(table.promptId, table.tagId)
		}
	}
)

export const staticPromptRelations = relations(staticPrompts, ({ many }) => ({
	tags: many(staticPromptTags)
}))

export const dynamicPromptRelations = relations(dynamicPrompts, ({ many }) => ({
	tags: many(dynamicPromptTags)
}))

export const tagStaticPromptRelations = relations(tags, ({ many }) => ({
	staticPrompts: many(staticPromptTags)
}))

export const tagDynamicPromptRelations = relations(tags, ({ many }) => ({
	dynamicPrompts: many(dynamicPromptTags)
}))

export const staticPromptTagRelations = relations(staticPromptTags, ({ one }) => ({
	tag: one(tags, {
		fields: [staticPromptTags.tagId],
		references: [tags.id]
	}),
	staticPrompt: one(staticPrompts, {
		fields: [staticPromptTags.promptId],
		references: [staticPrompts.id]
	})
}))

export const dynamicPromptTagRelations = relations(dynamicPromptTags, ({ one }) => ({
	tag: one(tags, {
		fields: [dynamicPromptTags.tagId],
		references: [tags.id]
	}),
	dynamicPrompt: one(dynamicPrompts, {
		fields: [dynamicPromptTags.promptId],
		references: [dynamicPrompts.id]
	})
}))
