from fastapi import APIRouter

router = APIRouter()
@router.get("/")
def read_root():
    return {"message": "Welcome to the API"}

@router.get("/items")
def get_items():
    return {"message": "List of items"}