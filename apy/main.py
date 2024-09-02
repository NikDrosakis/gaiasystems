import redis
from fastapi import FastAPI, WebSocket, WebSocketDisconnect
from routers import routing
from config import settings

app = FastAPI()
# Initialize Redis client
redis_client = redis.Redis(host='localhost', port=6379, password='yjF1f7uiHttcp')

# Include router with versioning
app.include_router(routing.router, prefix="/apy/v1")
@app.websocket("/{client_id:str}")
async def websocket_endpoint(websocket: WebSocket, client_id: str):
    await websocket.accept()
    try:
        while True:
            data = await websocket.receive_text()
            await websocket.send_text(f"Client {client_id} says: {data}")
    except WebSocketDisconnect:
        print(f"Client {client_id} disconnected")

async def redis_subscriber():
    pubsub.subscribe('broadcast_channel')
    for message in pubsub.listen():
        if message['type'] == 'message':
            # Broadcast the message to all WebSocket clients
            for ws in web_sockets:
                if ws.client_state == WebSocket.CONNECTED:
                    await ws.send_text(message['data'].decode())

if __name__ == '__main__':
    import uvicorn

    uvicorn.run(
        "main:app",
        host=settings.HOST,
        port=settings.PORT,
        reload=True,  # Enable auto-reload for development
        log_level=settings.LOG_LEVEL
    )
