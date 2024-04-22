CREATE TABLE `dynamic_prompt_tags` (
	`prompt_id` int NOT NULL,
	`tag_id` int NOT NULL,
	CONSTRAINT `dynamic_prompt_tags_prompt_id_tag_id` PRIMARY KEY(`prompt_id`,`tag_id`)
);
--> statement-breakpoint
CREATE TABLE `dynamic_prompts` (
	`id` serial AUTO_INCREMENT PRIMARY KEY NOT NULL,
	`title` varchar(256) NOT NULL,
	`prompt` text NOT NULL,
	`tokens` json
);
--> statement-breakpoint
CREATE TABLE `static_prompt_tags` (
	`prompt_id` int NOT NULL,
	`tag_id` int NOT NULL,
	CONSTRAINT `static_prompt_tags_prompt_id_tag_id` PRIMARY KEY(`prompt_id`,`tag_id`)
);
--> statement-breakpoint
CREATE TABLE `static_prompts` (
	`id` serial AUTO_INCREMENT PRIMARY KEY NOT NULL,
	`title` varchar(256) NOT NULL,
	`prompt` text NOT NULL
);
--> statement-breakpoint
CREATE TABLE `tags` (
	`id` serial AUTO_INCREMENT PRIMARY KEY NOT NULL,
	`text` varchar(20)
);
--> statement-breakpoint
ALTER TABLE `dynamic_prompt_tags` ADD CONSTRAINT `dynamic_prompt_tags_prompt_id_dynamic_prompts_id_fk` FOREIGN KEY (`prompt_id`) REFERENCES `dynamic_prompts`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `dynamic_prompt_tags` ADD CONSTRAINT `dynamic_prompt_tags_tag_id_tags_id_fk` FOREIGN KEY (`tag_id`) REFERENCES `tags`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `static_prompt_tags` ADD CONSTRAINT `static_prompt_tags_prompt_id_static_prompts_id_fk` FOREIGN KEY (`prompt_id`) REFERENCES `static_prompts`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `static_prompt_tags` ADD CONSTRAINT `static_prompt_tags_tag_id_tags_id_fk` FOREIGN KEY (`tag_id`) REFERENCES `tags`(`id`) ON DELETE no action ON UPDATE no action;