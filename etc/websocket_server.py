#!/usr/bin/env python

import asyncio
import websockets
from websockets.exceptions import ConnectionClosed
import datetime
import json
active_clients = list()

async def handler(websocket, path):
    global active_clients
    active_clients.append(websocket)
    print("Connect")
    while True:
        try:
            message = await websocket.recv()
            print(message)
            if message.startswith("bc"):
                print("Looping")
                for c in active_clients:
                    if c != websocket:
                        print("Sending!")
                        await c.send(json.dumps({"type": "alert", "message": message[2:]}))
            else:
                await consumer(websocket, message)
        except ConnectionClosed:
            break
    active_clients.remove(websocket)
    print("Disconnect")

async def consumer(websocket, message):
    print("< {}".format(message))
    greeting = {"type": "greeting"}
    await websocket.send(json.dumps(greeting))
    print("> {}".format(json.dumps(greeting)))

start_server = websockets.serve(handler, '0.0.0.0', 8765)

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
