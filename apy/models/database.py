# database.py
from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker
from config import settings

DATABASE_VIVALIBRO_URL = settings.DATABASE_VIVALIBRO
DATABASE_GPM_URL = settings.DATABASE_GPM

# Create engine and session
engine_vivalibro = create_engine(DATABASE_VIVALIBRO_URL)
engine_gpm = create_engine(DATABASE_GPM_URL)

SessionLocalVivalibro = sessionmaker(autocommit=False, autoflush=False, bind=engine_vivalibro)
SessionLocalGPM = sessionmaker(autocommit=False, autoflush=False, bind=engine_gpm)

Base = declarative_base()
