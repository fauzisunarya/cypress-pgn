import { Stack, Typography } from "@mui/material";
import { useState } from "react";

export default function Quotes() {

	const arrQuotes = [
		{
			text: 'The secret of getting ahead is getting started.',
			author: '~Mark Twain'
		},
		{
			text: 'The only way to do great work is to love what you do.',
			author: '~Steve Jobs'
		},
		{
			text: "Don't watch the clock, do what it does. Keep going.",
			author: '~Sam Levenson'
		},
		{
			text: "Success is not in what you have, but who you are.",
			author: '~Bo Bennett'
		},
		{
			text: "The future depends on what you do today.",
			author: '~Mahatma Gandhi'
		}
	];
	const randomIndex = Math.floor(Math.random() * arrQuotes.length);
	const [quotes] = useState(arrQuotes[randomIndex].text);
	const [author] = useState(arrQuotes[randomIndex].author);

	return (
		<>
			<Stack direction="row" justifyContent={'center'} alignItems={'center'}>
				<Typography variant="labelLarge" sx={(theme => ({color: theme.palette.grey[600]}))} fontWeight={400}>{quotes}</Typography>
			</Stack>

			<Stack direction="row" justifyContent={'center'} alignItems={'center'}>
				<Typography variant="labelLarge" sx={(theme => ({color: theme.palette.grey[600]}))} fontWeight={400}>{author}</Typography>
			</Stack>
		</>
	)
}