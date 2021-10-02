<template>
	<div class="tw-flex tw-flex-col tw-justify-between tw-h-screen">
		<div class="tw-flex tw-flex-col">
			<b-card class="tw-m-4 song-info">
				<b-card-body
					class="tw-flex tw-p-1 tw-justify-between"
					tw-flex-row
				>
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
					<b-button
						v-if="false"
						class="tw-h-8 tw-flex tw-items-center"
						@click="authSpotify"
						>Auth</b-button
					>
				</b-card-body>
			</b-card>
			<b-card class="tw-m-4 song-lyrics">
				<b-card-body class="tw-flex tw-justify-center">
					<vue-perfect-scrollbar
						:style="{ height: 50 + 'vh' }"
						:settings="{ wheelPropagation: false }"
						class="tw-w-full tw-flex tw-justify-center"
					>
						<span v-if="!lyrics">{{ $t("CouldNotFind") }}</span>
						<span
							v-else
							class="
								tw-whitespace-pre-line
								text-center
								tw-text-lg
							"
							>{{ lyrics.content }}</span
						>
					</vue-perfect-scrollbar>
				</b-card-body>
				<b-card-footer v-if="lyrics" class="tw-flex tw-justify-center">
					<span>{{ lyrics.copyright }}</span>
				</b-card-footer>
			</b-card>
		</div>
		<div>
			<b-card class="tw-m-4">
				<div>
					<a href="https://musixmatch.com" target="_">
						<img
							class="tw-h-8"
							src="/images/mxm/logo.png"
							title="Lyrics powered by www.musiXmatch.com"
						/>
					</a>
					<img
						v-if="lyrics && lyrics.tracking_url"
						v-show="false"
						:src="lyrics.tracking_url"
					/>
				</div>
			</b-card>
		</div>
	</div>
</template>

<script>
import {
	computed,
	onMounted,
	ref,
	reactive,
	onBeforeUnmount,
} from "@vue/composition-api";
import api from "@/repositories";
import VuePerfectScrollbar from "vue-perfect-scrollbar";

export default {
	name: "Home",
	metaInfo() {
		return {
			title:
				"Lyrico" +
				(this.song
					? ` | ${this.song.artists.join(", ")} - ${this.song.title}`
					: ""),
		};
	},
	components: { VuePerfectScrollbar },
	setup(props, { root }) {
		const currentlyPlaying = ref(null);
		const lyrics = ref(null);
		const poll = ref(null);
		const pollingInterval = ref(5000);
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
			api.spotify
				.getCurrentlyPlaying()
				.then(({ data }) => {
					if (
						!currentlyPlaying.value ||
						currentlyPlaying.value.id !== data.item.id
					) {
						currentlyPlaying.value = data.item;
						getLyrics();
					}
				})
				.catch(async (error) => {
					if (error.response) {
						if (error.response.status === 401) {
							authSpotify();
						}
					}
					console.log(error);
				});
		}

		async function getLyrics() {
			const { data } = await api.lyrics.show(
				"mxm",
				song.artists[0],
				song.title
			);
			lyrics.value = data;
		}

		onMounted(async () => {
			getCurrentlyPlaying();
			poll.value = setInterval(
				function () {
					getCurrentlyPlaying();
				}.bind(root),
				pollingInterval.value
			);
		});

		onBeforeUnmount(() => {
			clearInterval(poll.value);
			poll.value = null;
		});

		return {
			authSpotify,
			song,
			lyrics,
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
