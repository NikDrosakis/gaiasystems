# config.py
from pydantic_settings import BaseSettings

class Settings(BaseSettings):
    HOST: str
    PORT: int
    ENVIRONMENT: str
    LOG_LEVEL: str
    API_KEY: str
    DATABASE_VIVALIBRO: str
    DATABASE_GPM: str
    REDIS_URL: str

    class Config:
        env_file = ".env"

settings = Settings()
