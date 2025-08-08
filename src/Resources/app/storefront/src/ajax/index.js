// import Plugin from 'src/plugin-system/plugin.class';
// import HttpClient from 'src/service/http-client.service';
// import LoadingIndicator from 'src/utility/loading-indicator/loading-indicator.util'
//
//
// export default class AjaxPlugin extends Plugin{
//     init(){
//         this.el.innerHTML = LoadingIndicator.getTemplate();
//         this.client = new HttpClient(window.accessKey);
//         this.fetch();
//
//     }
//     fetch(){
//         this.client.get('/example' ,(responseText)=> {
//             const responseData = JSON.parse(responseText);
//             this.el.innerHTML = responseData.foo;
//         })
//
//
//     }
// }
import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';
import LoadingIndicator from 'src/utility/loading-indicator/loading-indicator.util';

export default class AjaxPlugin extends Plugin {
    init() {
        this.el.innerHTML = LoadingIndicator.getTemplate();
        this.client = new HttpClient(window.accessKey);
        this.fetch();

        // Poll every 5 seconds to update dynamically
        this.interval = setInterval(() => {
            this.fetch();
        }, 1000);
    }

    // fetch() {
    //     this.client.get('/example', (responseText) => {
    //         const responseData = JSON.parse(responseText);
    //         this.el.innerHTML = `
    //         <div style="
    //             font-family: 'Courier New', Courier, monospace;
    //             font-size: 2.5rem;
    //             color: #00ff00;
    //             background-color: #000;
    //             padding: 10px 20px;
    //             border-radius: 8px;
    //             text-align: center;
    //             width: fit-content;
    //             margin: auto;
    //             box-shadow: 0 0 10px #00ff00;
    //             ">
    //             ${responseData.timestamp}
    //         </div>
    //     `;
    //     });
    // }
    fetch() {
        this.client.get('/example', (responseText) => {
            const responseData = JSON.parse(responseText);
            const rawTimestamp = new Date(responseData.timestamp);

            // Format as HH:MM:SS
            const timeOnly = rawTimestamp.toLocaleTimeString('en-GB', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });

            this.el.innerHTML = `
            <div class="digital-clock">
                ${timeOnly}
            </div>
        `;
        });
    }



    // Optional: clear interval when plugin is destroyed
    destroy() {
        clearInterval(this.interval);
    }
}
