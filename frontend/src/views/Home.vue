<template>
	<div>
		<b-card class="tw-m-4 song-info">
			<b-card-body class="tw-flex tw-p-1 tw-justify-between" tw-flex-row>
				<div class="tw-flex tw-gap-2">
					<img
						v-if="song.cover"
						class="tw-w-28"
						:src="song.cover"
						alt=""
					/>
					<div class="tw-flex tw-flex-col tw-ml-2">
						<span class="tw-text-xl">{{ song.title }}</span>
						<span class="tw-text-lg">{{
							song.artists.join(", ")
						}}</span>
					</div>
				</div>
				<b-button class="tw-h-10" @click="authSpotify">Auth</b-button>
			</b-card-body>
		</b-card>
		<b-card class="tw-m-4 song-lyrics">
			<b-card-body class="tw-flex tw-justify-center">
				<span>{{ $t("CouldNotFind") }}</span>
			</b-card-body>
		</b-card>
	</div>
</template>

<script>
import { computed, onMounted, ref, reactive } from "@vue/composition-api";
import api from "@/repositories";

export default {
	name: "Home",
	setup(props, { root }) {
		const currentlyPlaying = ref(null);

		const song = reactive({
			artists: computed(() =>
				currentlyPlaying.value
					? currentlyPlaying.value.artists.map(
							(artist) => artist.name
					  )
					: []
			),
			title: computed(() =>
				currentlyPlaying.value ? currentlyPlaying.value.name : ""
			),
			cover: computed(() =>
				currentlyPlaying.value &&
				currentlyPlaying.value.album.images.length
					? currentlyPlaying.value.album.images[0].url
					: ""
			),
		});

		async function authSpotify() {
			const { data } = await api.spotify.getAuthUrl();
			window.location.href = data;
		}

		async function getCurrentlyPlaying() {
			const { data } = await api.spotify.getCurrentlyPlaying(
				root.$store.state.spotify.access_token
			);
			currentlyPlaying.value = data.item;
		}

		onMounted(() => {
			getCurrentlyPlaying();
		});

		return {
			authSpotify,
			song,
		};
	},
};
</script>

<style scoped>
.song-info,
.song-lyrics {
	background-color: #333;
	color: #f1f1f1;
}
</style>
