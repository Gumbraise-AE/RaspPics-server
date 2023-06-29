import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        livePath: String,
        picPath: String,
        userIp: String,
        externIp: String,
        gifPath: String,
    };

    static targets = ['image', 'video', 'firstButton', 'secondButton'];

    connect() {
        this.live();

        this.firstButtonTarget.addEventListener('click', () => {
            this.seePic();
        });

        this.secondButtonTarget.addEventListener('click', () => {
            this.seeGif();
        });
    }

    live() {
        if (!(this.userIpValue !== "10.241.249.5" && this.userIpValue !== "127.0.0.1" && this.userIpValue !== this.externIpValue)) {
            this.videoTarget.src = this.livePathValue;
        } else {
            this.seePic();
        }
    }

    seePic() {
        this.imageTarget.classList.toggle('hidden');
        this.videoTarget.classList.toggle('hidden');
        this.videoTarget.src = this.videoTarget.src === this.livePathValue ? "" : this.livePathValue;
        this.firstButtonTarget.innerText = this.firstButtonTarget.innerText === "WATCH THE VIDEO" ? "WATCH THE LAST PIC" : "WATCH THE VIDEO";
    }

    async seeGif() {
        this.imageTarget.classList.remove('hidden');
        this.videoTarget.classList.toggle('hidden', !this.videoTarget.classList.contains('hidden'));
        this.videoTarget.src = this.videoTarget.src === this.livePathValue ? "" : this.livePathValue;

        const response = await fetch(this.gifPathValue).then(() =>
            this.secondButtonTarget.innerText += "..."
        );
        this.imageTarget.src = await response.text();
    }
}