import type { Config } from 'drizzle-kit'

export default {
	schema: 'src/lib/server/schema.ts',
	out: './drizzle',
	driver: 'mysql2'
} satisfies Config
