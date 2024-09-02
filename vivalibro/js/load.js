/***********LOAD MAIN*****************/
if((G.page=="book" ||G.page=="books"||G.page=="libraries" || G.page=="writer"|| G.page=="publisher") && G.id=='') {
booklist();
}
//window.addEventListener('resize', updateCardSize);
//window.addEventListener('load', updateCardSize);

/***********INSTANTIATE WEBSOCKETS*****************/
//const socapi = WSClient(G.aconf.config.ws_port, 'indicator');
//const socapy = WSClient(3006, 'indicator2');
/***********LOAD WIDGETS*****************/
function start_widgets() {
    for (var wid in G.widgets) {
        for (var widid in G.widgets[wid]) {
            if (!!widid && !!G.widgets[wid][widid]) {
                var newDiv = document.createElement("div");
                newDiv.id = widid;
                newDiv.className = 'widget';
                var widiv = document.getElementById(wid);
                if (!!widiv) {
                    widiv.appendChild(newDiv);
                }
                loadCubo(`#${widid}`, `/cubos/index.php?cubo=${G.widgets[wid][widid]}&file=public.php`);
            }
        }
    }
}
start_widgets();
function soc_success() {
    console.log('WebSocket connection successfully opened');
    document.getElementById('indicator').className = 'green';
}
socapi.start(soc_success());




/*
const socapy = soc.start("3006",'indicator2');
                socapy.start().then(() =>{
                        document.getElementById('indicator2').className='green';
                    })
                    .catch(err => {
                        console.error('WebSocket connection');
                        document.getElementById('indicator2').className = 'red';
                        document.getElementById('c_active_users').innerHTML = '';
                        // Attempt to reconnect after 10 seconds
                        setTimeout(() => {
                            socapy.start()
                                .then(() =>{
                                    console.log('WebSocket connection successfully opened')
                                    document.getElementById('indicator2').className='green';
                                })
                                .catch(err => console.error('Reconnection failed:', err));
                        }, 10000);
                    });
*/

// Start the WebSocket connections
/*socapi.start()
    .then(() => {
        document.getElementById('indicatorMain').className = 'green';
    })
    .catch(err => {
        console.error('WebSocket connection for main failed');
        document.getElementById('indicator').className = 'red';
        document.getElementById('c_active_users').innerHTML = '';
        setTimeout(() => {
            socMain.start()
                .then(() => {
                    console.log('WebSocket connection for main successfully opened');
                    document.getElementById('indicatorMain').className = 'green';
                })
                .catch(err => console.error('Reconnection for main failed:', err));
        }, 10000);
    });

socapy.start()
    .then(() => {
        document.getElementById('indicatorApy').className = 'green';
    })
    .catch(err => {
        console.error('WebSocket connection for apy failed');
        document.getElementById('indicator2').className = 'red';
        //document.getElementById('c_active_users').innerHTML = '';
        setTimeout(() => {
            socApy.start()
                .then(() => {
                    console.log('WebSocket connection for apy successfully opened');
                    document.getElementById('indicator2').className = 'green';
                })
                .catch(err => console.error('Reconnection for apy failed:', err));
        }, 10000);
    });

            });

        }
}}
*/