USE [master]
GO
/****** Object:  Database [rrhh]    Script Date: 11/4/2023 11:33:24 PM ******/
CREATE DATABASE [rrhh]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'rrhh', FILENAME = N'C:\IBD115\rrhh.mdf' , SIZE = 8192KB , MAXSIZE = UNLIMITED, FILEGROWTH = 65536KB )
 LOG ON 
( NAME = N'rrhh_log', FILENAME = N'C:\IBD115\rrhh_log.ldf' , SIZE = 8192KB , MAXSIZE = 2048GB , FILEGROWTH = 65536KB )
 WITH CATALOG_COLLATION = DATABASE_DEFAULT, LEDGER = OFF
GO
ALTER DATABASE [rrhh] SET COMPATIBILITY_LEVEL = 150
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [rrhh].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [rrhh] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [rrhh] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [rrhh] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [rrhh] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [rrhh] SET ARITHABORT OFF 
GO
ALTER DATABASE [rrhh] SET AUTO_CLOSE ON 
GO
ALTER DATABASE [rrhh] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [rrhh] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [rrhh] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [rrhh] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [rrhh] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [rrhh] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [rrhh] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [rrhh] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [rrhh] SET  ENABLE_BROKER 
GO
ALTER DATABASE [rrhh] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [rrhh] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [rrhh] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [rrhh] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [rrhh] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [rrhh] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [rrhh] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [rrhh] SET RECOVERY SIMPLE 
GO
ALTER DATABASE [rrhh] SET  MULTI_USER 
GO
ALTER DATABASE [rrhh] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [rrhh] SET DB_CHAINING OFF 
GO
ALTER DATABASE [rrhh] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [rrhh] SET TARGET_RECOVERY_TIME = 60 SECONDS 
GO
ALTER DATABASE [rrhh] SET DELAYED_DURABILITY = DISABLED 
GO
ALTER DATABASE [rrhh] SET ACCELERATED_DATABASE_RECOVERY = OFF  
GO
EXEC sys.sp_db_vardecimal_storage_format N'rrhh', N'ON'
GO
ALTER DATABASE [rrhh] SET QUERY_STORE = OFF
GO
USE [rrhh]
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_AFP_LABORAL]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_AFP_LABORAL] (@SALARIO_BASE DECIMAL(18,2))
RETURNS DECIMAL (18,2)
AS
BEGIN
	RETURN (@SALARIO_BASE * 0.0725);
END;
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_AFP_PATRONAL]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[FN_OBT_AFP_PATRONAL] (@SALARIO_BASE DECIMAL(18,2))
RETURNS DECIMAL (18,2)
AS
BEGIN
	RETURN (@SALARIO_BASE * 0.0775);
END;
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_AGUINALDO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_AGUINALDO] (@salario FLOAT, @fecha_ingreso DATE)
RETURNS DECIMAL(18,2)
AS
BEGIN
    DECLARE @aguinaldo DECIMAL(18,2)

    -- C�lculo de aguinaldo
    DECLARE @anhos_trabajados DECIMAL = DATEDIFF(day, @fecha_ingreso, '2023-12-12') * 1.0 / 365.0
    SET @aguinaldo = (@salario / 30)

    -- C�lculo de d�as de aguinaldo seg�n antig�edad
    SET @aguinaldo = CASE 
        WHEN @anhos_trabajados >= 1.0 AND @anhos_trabajados <= 3.00 THEN @aguinaldo * 15
        WHEN @anhos_trabajados > 3.0 AND @anhos_trabajados <= 10.00 THEN @aguinaldo * 19
        WHEN @anhos_trabajados > 10.0 THEN @aguinaldo * 21
        ELSE 0 END

    RETURN @aguinaldo
END

-- SELECT dbo.FN_OBT_AGUINALDO(1000, '2020-01-01')

-- SELECT CAST('2023-12-12' AS DATE) - CAST('2020-01-01' AS DATE)
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_ANHOS_TRABAJADOS]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create FUNCTION [dbo].[FN_OBT_ANHOS_TRABAJADOS] (@FECHA_INGRESO DATE)
RETURNS VARCHAR(50)
AS
BEGIN
    DECLARE @anios INT
    DECLARE @meses INT
    DECLARE @hoy DATE = GETDATE()

    -- Cálculo de años y meses
    SET @anios = DATEDIFF(YEAR, @FECHA_INGRESO, @hoy)
    SET @meses = DATEDIFF(MONTH, DATEADD(YEAR, @anios, @FECHA_INGRESO), @hoy)

    -- Formato de salida
    RETURN CAST(@anios AS VARCHAR(2)) + ' año(s) y ' + CAST(@meses AS VARCHAR(2)) + ' mes(es)'
END
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_ISSS_LABORAL]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_ISSS_LABORAL] (@SALARIO_BASE DECIMAL(18,2))
RETURNS DECIMAL (18,2)
AS
BEGIN
	DECLARE @BASE_CALCULO DECIMAL = @SALARIO_BASE, @MONTO_ISSS DECIMAL;

	IF @BASE_CALCULO > 1000
	BEGIN
		SET @BASE_CALCULO = 1000
	END

	SET @MONTO_ISSS = (@BASE_CALCULO * 0.03);

	RETURN @MONTO_ISSS;
END;
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_ISSS_PATRONAL]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_ISSS_PATRONAL] (@SALARIO_BASE DECIMAL(18,2))
RETURNS DECIMAL (18,2)
AS
BEGIN
	DECLARE @BASE_CALCULO DECIMAL (18,2) = @SALARIO_BASE, @MONTO_ISSS DECIMAL (18,2) = 0.0;

	IF @BASE_CALCULO > 1000
	BEGIN
		SET @BASE_CALCULO = 1000
	END

	SET @MONTO_ISSS = (@BASE_CALCULO * 0.075);

	RETURN @MONTO_ISSS;
END
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_MONTO_RENTA]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_MONTO_RENTA] (@SUBTOTAL DECIMAL(18,2))
RETURNS DECIMAL (18,2)
AS
BEGIN
	DECLARE @MONTO_RENTA DECIMAL(18,2);
	SET @MONTO_RENTA = (SELECT CASE
		WHEN @SUBTOTAL <= 472.00 THEN 0
		WHEN @SUBTOTAL <= 895.24 THEN (@SUBTOTAL - 472.00) * 0.1 + 17.67
		WHEN @SUBTOTAL <= 2038.10 THEN (@SUBTOTAL - 895.24) * 0.2 + 60.00
		ELSE (@SUBTOTAL - 2038.10) * 0.3 + 288.57
	END);

	RETURN @MONTO_RENTA;
END;
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_PAGO_HORAS_EXTRA]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_PAGO_HORAS_EXTRA] (@ID_EMPLEADO INT, @SALARIO DECIMAL(18,2))
RETURNS DECIMAL(18,2)
AS
BEGIN
    DECLARE @PROPORCION DECIMAL(18,2) = (@SALARIO / 30 / 8)
    DECLARE @CANT_HE_ORD INT = 0, @CANT_HE_EXT INT = 0
    DECLARE @MONTO_TOTAL DECIMAL(18,2) = 0.0

    SELECT @CANT_HE_ORD = SUM(CANTIDAD)
     FROM HORAS_EXTRA
     WHERE ID_EMPLEADO = @ID_EMPLEADO
       AND TIPO = 'O'
    
    IF @CANT_HE_ORD IS NOT NULL AND @CANT_HE_ORD > 0
    BEGIN
        SET @MONTO_TOTAL = @MONTO_TOTAL + (@PROPORCION * @CANT_HE_ORD)
    END

    SELECT @CANT_HE_EXT = SUM(CANTIDAD)
     FROM HORAS_EXTRA
     WHERE ID_EMPLEADO = @ID_EMPLEADO
       AND TIPO = 'E'

    IF @CANT_HE_EXT IS NOT NULL AND @CANT_HE_EXT > 0
    BEGIN
        SET @MONTO_TOTAL = @MONTO_TOTAL + (@PROPORCION * @CANT_HE_EXT * 2)
    END
    
    RETURN @MONTO_TOTAL
END

-- SELECT dbo.FN_OBT_PAGO_HORAS_EXTRA (6, 700.0)

-- select * from horas_extra

-- select (700 / 30 / 8)
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_PLANILLA]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_PLANILLA](@Tipo_Consulta CHAR(1))
RETURNS @Result TABLE (
  Id_Empleado INT,
  Nombre VARCHAR(50),
  Fecha_Contratacion DATE,
  Tiempo_Laborado VARCHAR(500),
  Salario DECIMAL(18,2),
  AFP DECIMAL(18,2),
  ISSS DECIMAL(18,2),
  Renta DECIMAL(18,2),
  Salario_Neto DECIMAL(18,2)
)
AS
BEGIN
  -- Declare variables
  DECLARE @Id_Empleado INT
  DECLARE @Nombre VARCHAR(50)
  DECLARE @Fecha_Ingreso DATE
  DECLARE @Tiempo_Laborado VARCHAR(500)
  DECLARE @Salario DECIMAL(18,2)
  DECLARE @AFP DECIMAL(18,2)
  DECLARE @ISSS DECIMAL(18,2)
  DECLARE @Renta DECIMAL(18,2)
  DECLARE @Salario_Neto DECIMAL(18,2)

  -- Declare the cursor
  DECLARE empleados_cursor CURSOR FOR
    SELECT id_empleado, nombres + ' ' + apellidos, Fecha_Ingreso, salario
    FROM empleados where estado = 'A' order by nombres asc

  -- Open the cursor
  OPEN empleados_cursor

  -- Fetch the first row
  FETCH NEXT FROM empleados_cursor INTO @Id_Empleado, @Nombre, @Fecha_Ingreso, @Salario

  -- Loop through the rows
  WHILE @@FETCH_STATUS = 0
  BEGIN
    Select @Tiempo_Laborado = dbo.FN_OBT_TIEMPO_TRABAJADO(@Fecha_Ingreso)
	IF @Tipo_Consulta = 'P'
	BEGIN
		Select @AFP = dbo.FN_OBT_AFP_LABORAL(@Salario)
		Select @ISSS = dbo.FN_OBT_ISSS_LABORAL(@Salario)
	END
	ELSE
	BEGIN
		Select @AFP = dbo.FN_OBT_AFP_PATRONAL(@Salario)
		Select @ISSS = dbo.FN_OBT_ISSS_PATRONAL(@Salario)
	END
	
  IF @Tipo_Consulta = 'P'
	BEGIN
		SET @Salario_Neto = @Salario - @AFP - @ISSS
	END
	ELSE
	BEGIN
		SET @Salario_Neto = @Salario + @AFP + @ISSS
	END
	

	IF @Tipo_Consulta = 'P'
	BEGIN
		Select @Renta = dbo.FN_OBT_MONTO_RENTA(@Salario_Neto)
	END
	ELSE
	BEGIN
		SET @Renta = 0.0
	END

	SET @Salario_Neto = @Salario_Neto - @Renta

	/*IF CAST(@Tiempo_Laborado AS INT) > 0
	BEGIN
	  SET @Tiempo_Laborado = @Tiempo_Laborado + ' a�os'
	END
	ELSE
	BEGIN
	  SET @Tiempo_Laborado = ' menos de un a�o'
	END*/

    -- Insert the data into the table variable
    INSERT INTO @Result (Id_Empleado, Nombre, Fecha_Contratacion, Tiempo_Laborado, Salario, AFP, ISSS, Renta, Salario_Neto)
    VALUES (@Id_Empleado, @Nombre, @Fecha_Ingreso, @Tiempo_Laborado, @Salario, @AFP, @ISSS, @Renta, @Salario_Neto)

    -- Fetch the next row
    FETCH NEXT FROM empleados_cursor INTO @Id_Empleado, @Nombre, @Fecha_Ingreso, @Salario
  END

  -- Close and deallocate the cursor
  CLOSE empleados_cursor
  DEALLOCATE empleados_cursor

  -- Return the table variable
  RETURN
END
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_TIEMPO_TRABAJADO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_TIEMPO_TRABAJADO] (@FECHA_INGRESO DATE)
RETURNS VARCHAR(50)
AS
BEGIN
    DECLARE @anios INT
    DECLARE @meses INT
	DECLARE @dias INT
    DECLARE @hoy DATE = GETDATE()
	DECLARE @tiempo_laborado VARCHAR(50)

    -- Cálculo de años y meses
    SET @anios = DATEDIFF(YEAR, @FECHA_INGRESO, @hoy)
    SET @meses = DATEDIFF(MONTH, DATEADD(YEAR, @anios, @FECHA_INGRESO), @hoy)

	IF @anios > 0
	BEGIN
		IF @anios = 1
		BEGIN
			SET @tiempo_laborado = CAST(@anios AS CHAR) + ' año '
		END
		ELSE
		BEGIN
			SET @tiempo_laborado = CAST(@anios AS CHAR) + ' años '
		END
	END

	IF @meses > 0
	BEGIN
		IF LEN(@tiempo_laborado) IS NOT NULL OR LEN(@tiempo_laborado) > 0
		BEGIN
			SET @tiempo_laborado = @tiempo_laborado + ' y '
		END

		IF @meses = 1
		BEGIN
			SET @tiempo_laborado = @tiempo_laborado + CAST(@meses AS CHAR) + ' mes '
		END
		ELSE
		BEGIN
			SET @tiempo_laborado = @tiempo_laborado + CAST(@meses AS CHAR) + ' meses '
		END
	END

	IF LEN(@tiempo_laborado) IS NULL OR LEN(@tiempo_laborado) = 0
	BEGIN
		SET @dias = DATEDIFF(DAY, @FECHA_INGRESO, @hoy)

		IF @dias = 1
		BEGIN
			SET @tiempo_laborado = TRIM(CAST(@dias AS CHAR)) + ' día '
		END
		ELSE
		BEGIN
			SET @tiempo_laborado = TRIM(CAST(@dias AS CHAR)) + ' días '
		END
	END

    -- Formato de salida
    RETURN @tiempo_laborado
END

-- SELECT [dbo].[FN_OBT_TIEMPO_TRABAJADO] ('04/10/2023')
-- SELECT DATEDIFF(DAY, '10/04/2023', GETDATE())
GO
/****** Object:  Table [dbo].[HORAS_EXTRA]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[HORAS_EXTRA](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[FECHA] [date] NOT NULL,
	[TIPO] [char](1) NOT NULL,
	[CANTIDAD] [int] NOT NULL,
	[ID_EMPLEADO] [int] NOT NULL,
 CONSTRAINT [PK_HORAS_EXTRA] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_HORASEXTRA]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create function [dbo].[FN_OBT_HORASEXTRA](@id_horaExtra int)
RETURNS TABLE
AS
RETURN
	SELECT TOP 1 ID, FECHA, TIPO, CANTIDAD, ID_EMPLEADO FROM HORAS_EXTRA WHERE ID = @id_horaExtra
GO
/****** Object:  Table [dbo].[AUSENCIAS]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[AUSENCIAS](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[fecha] [date] NOT NULL,
	[id_empleado] [int] NOT NULL,
	[comentario] [varchar](500) NULL,
	[tipo] [char](1) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_AUSENCIA]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_AUSENCIA] (@ID_AUSENCIA INT)
RETURNS TABLE
AS
RETURN
    SELECT top 1 id, FORMAT(fecha, 'dd/MM/yyyy') fecha, tipo, comentario, id_empleado FROM AUSENCIAS WHERE id = @ID_AUSENCIA;
GO
/****** Object:  Table [dbo].[roles]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[roles](
	[ID_ROL] [int] IDENTITY(1,1) NOT NULL,
	[NOMBRE] [nvarchar](100) NOT NULL,
	[ESTADO] [char](1) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[ID_ROL] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_ROL]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_ROL] (@ID_ROL INT)
RETURNS TABLE
AS
RETURN
    SELECT TOP 1 id_rol, nombre, estado FROM roles WHERE id_rol = @ID_ROL;
GO
/****** Object:  Table [dbo].[departamentos]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[departamentos](
	[id_depto] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [nvarchar](100) NOT NULL,
	[estado] [char](1) NOT NULL,
 CONSTRAINT [PK__departam__11F904ABDD9E3304] PRIMARY KEY CLUSTERED 
(
	[id_depto] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_DEPARTAMENTO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_DEPARTAMENTO] (@ID_DEPTO INT)
RETURNS TABLE
AS
RETURN
    SELECT TOP 1 id_depto, nombre, estado FROM departamentos WHERE id_depto = @ID_DEPTO;
GO
/****** Object:  Table [dbo].[usuarios]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[usuarios](
	[ID_USUARIO] [int] IDENTITY(1,1) NOT NULL,
	[NOMBRES] [nvarchar](100) NULL,
	[APELLIDOS] [nvarchar](100) NULL,
	[EMAIL] [varchar](120) NOT NULL,
	[PASSWORD] [varchar](500) NOT NULL,
	[ID_ROL] [int] NOT NULL,
	[IMAGEN] [varchar](250) NULL,
	[FECHA_CREACION] [date] NOT NULL,
	[id_empleado] [int] NULL,
 CONSTRAINT [PK__usuarios__91136B90B76CD546] PRIMARY KEY CLUSTERED 
(
	[ID_USUARIO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_USUARIO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_USUARIO] (@ID_USUARIO INT)
RETURNS TABLE
AS
RETURN
    SELECT TOP 1 id_usuario, nombres, apellidos, imagen, id_rol, email, password, fecha_creacion, id_empleado FROM usuarios WHERE id_usuario = @ID_USUARIO;
GO
/****** Object:  Table [dbo].[empleados]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[empleados](
	[id_empleado] [int] IDENTITY(1,1) NOT NULL,
	[nombres] [nvarchar](100) NOT NULL,
	[apellidos] [nvarchar](100) NOT NULL,
	[cargo] [nvarchar](100) NOT NULL,
	[fecha_ingreso] [date] NOT NULL,
	[fecha_salida] [date] NULL,
	[salario] [decimal](10, 0) NOT NULL,
	[estado] [char](1) NOT NULL,
	[id_depto] [int] NOT NULL,
	[tipo_salida] [char](1) NULL,
	[estado_indem] [char](1) NULL,
	[id_tipo_contrato] [int] NOT NULL,
 CONSTRAINT [PK__empleado__88B51394DF3A875A] PRIMARY KEY CLUSTERED 
(
	[id_empleado] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_EMPLEADO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_EMPLEADO] (@ID_EMPLEADO INT)
RETURNS TABLE
AS
RETURN
    SELECT id_empleado, nombres, apellidos, cargo, FORMAT(fecha_ingreso, 'dd/MM/yyyy') fecha_ingreso, FORMAT(fecha_salida, 'dd/MM/yyyy') fecha_salida, salario, estado, id_depto, tipo_salida, estado_indem, id_tipo_contrato FROM empleados WHERE id_empleado = @ID_EMPLEADO;
GO
/****** Object:  Table [dbo].[ROLES_MODULO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ROLES_MODULO](
	[ID_ROL] [int] NOT NULL,
	[ID_MODULO] [int] NOT NULL,
	[PUEDE_INSERTAR] [char](1) NOT NULL,
	[PUEDE_ACTUALIZAR] [char](1) NOT NULL,
	[PUEDE_ELIMINAR] [char](1) NOT NULL,
	[id] [int] IDENTITY(1,1) NOT NULL,
	[PUEDE_CONSULTAR] [char](1) NOT NULL,
 CONSTRAINT [PK__ROLES_MO__3213E83F85455E00] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_PERMISO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_PERMISO] (@id_permiso INT)
RETURNS TABLE
AS
RETURN
	SELECT id, ID_ROL, ID_MODULO, PUEDE_INSERTAR, PUEDE_ACTUALIZAR, PUEDE_ELIMINAR, PUEDE_CONSULTAR FROM ROLES_MODULO WHERE id = @id_permiso
GO
/****** Object:  Table [dbo].[PRESTACIONES]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PRESTACIONES](
	[id_prestacion] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [nvarchar](100) NOT NULL,
	[porcentaje] [decimal](5, 2) NOT NULL,
	[monto] [decimal](17, 2) NOT NULL,
	[techo_inferior] [decimal](17, 2) NOT NULL,
	[techo_superior] [decimal](17, 2) NOT NULL,
	[estado] [char](1) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id_prestacion] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_PRESTACION]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[FN_OBT_PRESTACION] (@ID_PRESTACION INT)
RETURNS TABLE
AS
RETURN
    SELECT TOP 1 id_prestacion, nombre, porcentaje, monto, techo_inferior, techo_superior, estado FROM PRESTACIONES WHERE id_prestacion = @ID_PRESTACION;

GO
/****** Object:  UserDefinedFunction [dbo].[FN_OBT_MESES_TRABAJADOS]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[FN_OBT_MESES_TRABAJADOS] (@FECHA_INGRESO DATE)
RETURNS TABLE
AS
RETURN
    SELECT DATEDIFF(MONTH, CONVERT(datetime, @FECHA_INGRESO, 103), CONVERT(datetime, GETDATE(), 103)) AS meses_trabajados
GO
/****** Object:  Table [dbo].[descuentos]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[descuentos](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[fecha] [date] NOT NULL,
	[id_empleado] [int] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MODULOS]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MODULOS](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [varchar](50) NOT NULL,
	[estado] [char](1) NOT NULL,
	[tab_name] [nvarchar](50) NOT NULL,
	[ruta] [nvarchar](50) NOT NULL,
 CONSTRAINT [PK_MODULOS] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PRESTACIONES_TIPO_CONT]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PRESTACIONES_TIPO_CONT](
	[id_tipo_cont] [int] NOT NULL,
	[id_prestacion] [int] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TIPOS_CONTRATO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TIPOS_CONTRATO](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[Nombre] [nvarchar](100) NOT NULL,
	[Descripcion] [nvarchar](100) NULL,
 CONSTRAINT [PK_Tipos_contrato] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
SET IDENTITY_INSERT [dbo].[AUSENCIAS] ON 
GO
INSERT [dbo].[AUSENCIAS] ([id], [fecha], [id_empleado], [comentario], [tipo]) VALUES (1, CAST(N'2023-10-04' AS Date), 6, N'Actividad académica', N'P')
GO
INSERT [dbo].[AUSENCIAS] ([id], [fecha], [id_empleado], [comentario], [tipo]) VALUES (2, CAST(N'2023-11-02' AS Date), 6, N'Sin detalles', N'A')
GO
INSERT [dbo].[AUSENCIAS] ([id], [fecha], [id_empleado], [comentario], [tipo]) VALUES (3, CAST(N'2023-11-09' AS Date), 6, N'Gripe común', N'I')
GO
SET IDENTITY_INSERT [dbo].[AUSENCIAS] OFF
GO
SET IDENTITY_INSERT [dbo].[departamentos] ON 
GO
INSERT [dbo].[departamentos] ([id_depto], [nombre], [estado]) VALUES (1, N'Administración TI', N'A')
GO
SET IDENTITY_INSERT [dbo].[departamentos] OFF
GO
SET IDENTITY_INSERT [dbo].[empleados] ON 
GO
INSERT [dbo].[empleados] ([id_empleado], [nombres], [apellidos], [cargo], [fecha_ingreso], [fecha_salida], [salario], [estado], [id_depto], [tipo_salida], [estado_indem], [id_tipo_contrato]) VALUES (6, N'Kevin', N'Núñez', N'Asistente', CAST(N'2021-07-23' AS Date), CAST(N'1900-01-01' AS Date), CAST(800 AS Decimal(10, 0)), N'I', 1, N'D', N'P', 1)
GO
INSERT [dbo].[empleados] ([id_empleado], [nombres], [apellidos], [cargo], [fecha_ingreso], [fecha_salida], [salario], [estado], [id_depto], [tipo_salida], [estado_indem], [id_tipo_contrato]) VALUES (8, N'Cicely', N'Hernández', N'Soporte', CAST(N'2015-09-01' AS Date), CAST(N'1900-01-01' AS Date), CAST(2000 AS Decimal(10, 0)), N'A', 1, N'D', N'P', 1)
GO
INSERT [dbo].[empleados] ([id_empleado], [nombres], [apellidos], [cargo], [fecha_ingreso], [fecha_salida], [salario], [estado], [id_depto], [tipo_salida], [estado_indem], [id_tipo_contrato]) VALUES (9, N'Erick', N'Hernández', N'Recursos Humanos', CAST(N'2021-02-03' AS Date), CAST(N'1900-01-01' AS Date), CAST(1200 AS Decimal(10, 0)), N'A', 1, N'R', N'P', 1)
GO
INSERT [dbo].[empleados] ([id_empleado], [nombres], [apellidos], [cargo], [fecha_ingreso], [fecha_salida], [salario], [estado], [id_depto], [tipo_salida], [estado_indem], [id_tipo_contrato]) VALUES (10, N'Roberto', N'Gutiérrez', N'Desarrollador', CAST(N'2015-01-01' AS Date), CAST(N'1900-01-01' AS Date), CAST(2000 AS Decimal(10, 0)), N'A', 1, N'R', N'P', 1)
GO
SET IDENTITY_INSERT [dbo].[empleados] OFF
GO
SET IDENTITY_INSERT [dbo].[HORAS_EXTRA] ON 
GO
INSERT [dbo].[HORAS_EXTRA] ([ID], [FECHA], [TIPO], [CANTIDAD], [ID_EMPLEADO]) VALUES (1, CAST(N'2023-11-03' AS Date), N'E', 10, 6)
GO
INSERT [dbo].[HORAS_EXTRA] ([ID], [FECHA], [TIPO], [CANTIDAD], [ID_EMPLEADO]) VALUES (2, CAST(N'2023-11-02' AS Date), N'O', 13, 6)
GO
SET IDENTITY_INSERT [dbo].[HORAS_EXTRA] OFF
GO
SET IDENTITY_INSERT [dbo].[MODULOS] ON 
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (1, N'Planilla', N'A', N'tab_planilla', N'../planilla/index.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (2, N'Costos', N'A', N'tab_costeo', N'../costeo/index.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (3, N'Ausencias', N'A', N'tab_ausencias', N'../ausencias/index.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (4, N'Áreas', N'A', N'tab_deptos', N'../departamentos/index.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (5, N'Empleados', N'A', N'tab_empleados', N'../empleados/index.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (6, N'Usuarios', N'A', N'tab_usuarios', N'../usuarios/index.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (7, N'Permisos', N'A', N'tab_roles', N'../roles/index.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (8, N'Indemnizaciones', N'A', N'tab_indem', N'../planilla/indemnizaciones.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (9, N'Asignación de Permisos', N'A', N'tab_permisos', N'../permisos/index.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (10, N'Prestaciones', N'A', N'tab_prestaciones', N'../prestaciones/index.php')
GO
INSERT [dbo].[MODULOS] ([id], [nombre], [estado], [tab_name], [ruta]) VALUES (11, N'Horas Extras', N'A', N'tab_horExtra', N'../horasExtras/index.php')
GO
SET IDENTITY_INSERT [dbo].[MODULOS] OFF
GO
SET IDENTITY_INSERT [dbo].[PRESTACIONES] ON 
GO
INSERT [dbo].[PRESTACIONES] ([id_prestacion], [nombre], [porcentaje], [monto], [techo_inferior], [techo_superior], [estado]) VALUES (1, N'AFP Laboral', CAST(7.25 AS Decimal(5, 2)), CAST(0.00 AS Decimal(17, 2)), CAST(0.01 AS Decimal(17, 2)), CAST(99999.99 AS Decimal(17, 2)), N'A')
GO
INSERT [dbo].[PRESTACIONES] ([id_prestacion], [nombre], [porcentaje], [monto], [techo_inferior], [techo_superior], [estado]) VALUES (2, N'AFP Patronal', CAST(7.75 AS Decimal(5, 2)), CAST(0.00 AS Decimal(17, 2)), CAST(0.01 AS Decimal(17, 2)), CAST(99999.99 AS Decimal(17, 2)), N'A')
GO
INSERT [dbo].[PRESTACIONES] ([id_prestacion], [nombre], [porcentaje], [monto], [techo_inferior], [techo_superior], [estado]) VALUES (3, N'ISSS Laboral', CAST(3.00 AS Decimal(5, 2)), CAST(0.00 AS Decimal(17, 2)), CAST(0.01 AS Decimal(17, 2)), CAST(10000.00 AS Decimal(17, 2)), N'A')
GO
INSERT [dbo].[PRESTACIONES] ([id_prestacion], [nombre], [porcentaje], [monto], [techo_inferior], [techo_superior], [estado]) VALUES (4, N'ISSS Patronal', CAST(7.50 AS Decimal(5, 2)), CAST(0.00 AS Decimal(17, 2)), CAST(0.01 AS Decimal(17, 2)), CAST(1000.00 AS Decimal(17, 2)), N'A')
GO
SET IDENTITY_INSERT [dbo].[PRESTACIONES] OFF
GO
SET IDENTITY_INSERT [dbo].[roles] ON 
GO
INSERT [dbo].[roles] ([ID_ROL], [NOMBRE], [ESTADO]) VALUES (1, N'Administración', N'A')
GO
INSERT [dbo].[roles] ([ID_ROL], [NOMBRE], [ESTADO]) VALUES (2, N'Contabilidad', N'A')
GO
INSERT [dbo].[roles] ([ID_ROL], [NOMBRE], [ESTADO]) VALUES (3, N'RRHH', N'A')
GO
INSERT [dbo].[roles] ([ID_ROL], [NOMBRE], [ESTADO]) VALUES (4, N'Soporte', N'A')
GO
SET IDENTITY_INSERT [dbo].[roles] OFF
GO
SET IDENTITY_INSERT [dbo].[ROLES_MODULO] ON 
GO
INSERT [dbo].[ROLES_MODULO] ([ID_ROL], [ID_MODULO], [PUEDE_INSERTAR], [PUEDE_ACTUALIZAR], [PUEDE_ELIMINAR], [id], [PUEDE_CONSULTAR]) VALUES (2, 2, N'S', N'S', N'S', 1, N'S')
GO
INSERT [dbo].[ROLES_MODULO] ([ID_ROL], [ID_MODULO], [PUEDE_INSERTAR], [PUEDE_ACTUALIZAR], [PUEDE_ELIMINAR], [id], [PUEDE_CONSULTAR]) VALUES (3, 1, N'S', N'S', N'S', 2, N'S')
GO
INSERT [dbo].[ROLES_MODULO] ([ID_ROL], [ID_MODULO], [PUEDE_INSERTAR], [PUEDE_ACTUALIZAR], [PUEDE_ELIMINAR], [id], [PUEDE_CONSULTAR]) VALUES (4, 6, N'S', N'S', N'S', 3, N'S')
GO
SET IDENTITY_INSERT [dbo].[ROLES_MODULO] OFF
GO
SET IDENTITY_INSERT [dbo].[TIPOS_CONTRATO] ON 
GO
INSERT [dbo].[TIPOS_CONTRATO] ([id], [Nombre], [Descripcion]) VALUES (1, N'Fijo', N'Prueba')
GO
INSERT [dbo].[TIPOS_CONTRATO] ([id], [Nombre], [Descripcion]) VALUES (2, N'Temporal', N'Prueba')
GO
SET IDENTITY_INSERT [dbo].[TIPOS_CONTRATO] OFF
GO
SET IDENTITY_INSERT [dbo].[usuarios] ON 
GO
INSERT [dbo].[usuarios] ([ID_USUARIO], [NOMBRES], [APELLIDOS], [EMAIL], [PASSWORD], [ID_ROL], [IMAGEN], [FECHA_CREACION], [id_empleado]) VALUES (2, N'Roberto', N'Gutiérrez Dubón', N'gd19016@ues.edu.sv', N'123456', 1, NULL, CAST(N'2023-10-21' AS Date), 10)
GO
INSERT [dbo].[usuarios] ([ID_USUARIO], [NOMBRES], [APELLIDOS], [EMAIL], [PASSWORD], [ID_ROL], [IMAGEN], [FECHA_CREACION], [id_empleado]) VALUES (4, N'Erick', N'Hernández', N'hm11019@ues.edu.sv', N'123456', 2, NULL, CAST(N'2023-11-01' AS Date), 9)
GO
INSERT [dbo].[usuarios] ([ID_USUARIO], [NOMBRES], [APELLIDOS], [EMAIL], [PASSWORD], [ID_ROL], [IMAGEN], [FECHA_CREACION], [id_empleado]) VALUES (6, N'Cicely', N'Hernández', N'hr18015@ues.edu.sv', N'123456', 2, NULL, CAST(N'2023-11-04' AS Date), 8)
GO
INSERT [dbo].[usuarios] ([ID_USUARIO], [NOMBRES], [APELLIDOS], [EMAIL], [PASSWORD], [ID_ROL], [IMAGEN], [FECHA_CREACION], [id_empleado]) VALUES (7, N'Kevin', N'Núñez', N'nm19010@ues.edu.sv', N'123456', 4, NULL, CAST(N'2023-11-04' AS Date), 6)
GO
SET IDENTITY_INSERT [dbo].[usuarios] OFF
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [idx_empleados_estado]    Script Date: 11/4/2023 11:33:24 PM ******/
CREATE NONCLUSTERED INDEX [idx_empleados_estado] ON [dbo].[empleados]
(
	[estado] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [idx_empleados_fecha_ingreso]    Script Date: 11/4/2023 11:33:24 PM ******/
CREATE NONCLUSTERED INDEX [idx_empleados_fecha_ingreso] ON [dbo].[empleados]
(
	[fecha_ingreso] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [idx_empleados_id_depto]    Script Date: 11/4/2023 11:33:24 PM ******/
CREATE NONCLUSTERED INDEX [idx_empleados_id_depto] ON [dbo].[empleados]
(
	[id_depto] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [idx_empleados_salario]    Script Date: 11/4/2023 11:33:24 PM ******/
CREATE NONCLUSTERED INDEX [idx_empleados_salario] ON [dbo].[empleados]
(
	[salario] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [UQ_ID_ROL_ID_MODULO]    Script Date: 11/4/2023 11:33:24 PM ******/
ALTER TABLE [dbo].[ROLES_MODULO] ADD  CONSTRAINT [UQ_ID_ROL_ID_MODULO] UNIQUE NONCLUSTERED 
(
	[ID_ROL] ASC,
	[ID_MODULO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
ALTER TABLE [dbo].[departamentos] ADD  CONSTRAINT [DF__departame__estad__45F365D3]  DEFAULT ('A') FOR [estado]
GO
ALTER TABLE [dbo].[empleados] ADD  CONSTRAINT [DF__empleados__salar__2C3393D0]  DEFAULT ((365)) FOR [salario]
GO
ALTER TABLE [dbo].[empleados] ADD  CONSTRAINT [DF__empleados__estad__2D27B809]  DEFAULT ('A') FOR [estado]
GO
ALTER TABLE [dbo].[empleados] ADD  CONSTRAINT [DF__empleados__tipo___2E1BDC42]  DEFAULT ('R') FOR [tipo_salida]
GO
ALTER TABLE [dbo].[empleados] ADD  CONSTRAINT [DF__empleados__estad__2F10007B]  DEFAULT ('P') FOR [estado_indem]
GO
ALTER TABLE [dbo].[PRESTACIONES] ADD  DEFAULT ((0.00)) FOR [porcentaje]
GO
ALTER TABLE [dbo].[PRESTACIONES] ADD  DEFAULT ((0.00)) FOR [techo_inferior]
GO
ALTER TABLE [dbo].[PRESTACIONES] ADD  DEFAULT ((0.01)) FOR [techo_superior]
GO
ALTER TABLE [dbo].[PRESTACIONES] ADD  DEFAULT ('A') FOR [estado]
GO
ALTER TABLE [dbo].[usuarios] ADD  CONSTRAINT [DF__usuarios__NOMBRE__4E88ABD4]  DEFAULT (NULL) FOR [NOMBRES]
GO
ALTER TABLE [dbo].[usuarios] ADD  CONSTRAINT [DF__usuarios__APELLI__4F7CD00D]  DEFAULT (NULL) FOR [APELLIDOS]
GO
ALTER TABLE [dbo].[usuarios] ADD  CONSTRAINT [DF__usuarios__IMAGEN__5070F446]  DEFAULT (NULL) FOR [IMAGEN]
GO
ALTER TABLE [dbo].[usuarios] ADD  CONSTRAINT [DF__usuarios__FECHA___5165187F]  DEFAULT (getdate()) FOR [FECHA_CREACION]
GO
ALTER TABLE [dbo].[usuarios] ADD  CONSTRAINT [DF__usuarios__id_emp__52593CB8]  DEFAULT (NULL) FOR [id_empleado]
GO
ALTER TABLE [dbo].[AUSENCIAS]  WITH CHECK ADD  CONSTRAINT [FK_ausencias_empleados] FOREIGN KEY([id_empleado])
REFERENCES [dbo].[empleados] ([id_empleado])
GO
ALTER TABLE [dbo].[AUSENCIAS] CHECK CONSTRAINT [FK_ausencias_empleados]
GO
ALTER TABLE [dbo].[descuentos]  WITH CHECK ADD  CONSTRAINT [FK_descuentos_empleados] FOREIGN KEY([id_empleado])
REFERENCES [dbo].[empleados] ([id_empleado])
GO
ALTER TABLE [dbo].[descuentos] CHECK CONSTRAINT [FK_descuentos_empleados]
GO
ALTER TABLE [dbo].[empleados]  WITH CHECK ADD  CONSTRAINT [FK_empleados_departamentos] FOREIGN KEY([id_depto])
REFERENCES [dbo].[departamentos] ([id_depto])
GO
ALTER TABLE [dbo].[empleados] CHECK CONSTRAINT [FK_empleados_departamentos]
GO
ALTER TABLE [dbo].[empleados]  WITH CHECK ADD  CONSTRAINT [FK_empleados_Tipos_contrato] FOREIGN KEY([id_tipo_contrato])
REFERENCES [dbo].[TIPOS_CONTRATO] ([id])
GO
ALTER TABLE [dbo].[empleados] CHECK CONSTRAINT [FK_empleados_Tipos_contrato]
GO
ALTER TABLE [dbo].[HORAS_EXTRA]  WITH CHECK ADD  CONSTRAINT [FK_HORAS_EXTRA_empleados] FOREIGN KEY([ID_EMPLEADO])
REFERENCES [dbo].[empleados] ([id_empleado])
GO
ALTER TABLE [dbo].[HORAS_EXTRA] CHECK CONSTRAINT [FK_HORAS_EXTRA_empleados]
GO
ALTER TABLE [dbo].[PRESTACIONES_TIPO_CONT]  WITH CHECK ADD  CONSTRAINT [FK_Prestaciones_tipo_cont_prestaciones] FOREIGN KEY([id_prestacion])
REFERENCES [dbo].[PRESTACIONES] ([id_prestacion])
GO
ALTER TABLE [dbo].[PRESTACIONES_TIPO_CONT] CHECK CONSTRAINT [FK_Prestaciones_tipo_cont_prestaciones]
GO
ALTER TABLE [dbo].[PRESTACIONES_TIPO_CONT]  WITH CHECK ADD  CONSTRAINT [FK_Prestaciones_tipo_cont_Tipos_contrato] FOREIGN KEY([id_tipo_cont])
REFERENCES [dbo].[TIPOS_CONTRATO] ([id])
GO
ALTER TABLE [dbo].[PRESTACIONES_TIPO_CONT] CHECK CONSTRAINT [FK_Prestaciones_tipo_cont_Tipos_contrato]
GO
ALTER TABLE [dbo].[ROLES_MODULO]  WITH CHECK ADD  CONSTRAINT [FK_ROLES_MODULO_MODULOS] FOREIGN KEY([ID_MODULO])
REFERENCES [dbo].[MODULOS] ([id])
GO
ALTER TABLE [dbo].[ROLES_MODULO] CHECK CONSTRAINT [FK_ROLES_MODULO_MODULOS]
GO
ALTER TABLE [dbo].[ROLES_MODULO]  WITH CHECK ADD  CONSTRAINT [FK_ROLES_MODULO_roles] FOREIGN KEY([ID_ROL])
REFERENCES [dbo].[roles] ([ID_ROL])
GO
ALTER TABLE [dbo].[ROLES_MODULO] CHECK CONSTRAINT [FK_ROLES_MODULO_roles]
GO
ALTER TABLE [dbo].[usuarios]  WITH CHECK ADD  CONSTRAINT [FK_usuarios_empleados] FOREIGN KEY([id_empleado])
REFERENCES [dbo].[empleados] ([id_empleado])
GO
ALTER TABLE [dbo].[usuarios] CHECK CONSTRAINT [FK_usuarios_empleados]
GO
ALTER TABLE [dbo].[usuarios]  WITH CHECK ADD  CONSTRAINT [FK_usuarios_roles] FOREIGN KEY([ID_ROL])
REFERENCES [dbo].[roles] ([ID_ROL])
GO
ALTER TABLE [dbo].[usuarios] CHECK CONSTRAINT [FK_usuarios_roles]
GO
ALTER TABLE [dbo].[AUSENCIAS]  WITH CHECK ADD  CONSTRAINT [CHK_TIPO_AUSENCIA] CHECK  (([tipo]='P' OR [tipo]='I' OR [tipo]='A'))
GO
ALTER TABLE [dbo].[AUSENCIAS] CHECK CONSTRAINT [CHK_TIPO_AUSENCIA]
GO
ALTER TABLE [dbo].[empleados]  WITH CHECK ADD  CONSTRAINT [CHK_EMPLEADO_ESTADO] CHECK  (([ESTADO]='I' OR [ESTADO]='A'))
GO
ALTER TABLE [dbo].[empleados] CHECK CONSTRAINT [CHK_EMPLEADO_ESTADO]
GO
ALTER TABLE [dbo].[empleados]  WITH CHECK ADD  CONSTRAINT [CHK_EMPLEADO_ESTADO_INDEM] CHECK  (([ESTADO_INDEM]='P' OR [ESTADO_INDEM]='L'))
GO
ALTER TABLE [dbo].[empleados] CHECK CONSTRAINT [CHK_EMPLEADO_ESTADO_INDEM]
GO
ALTER TABLE [dbo].[empleados]  WITH CHECK ADD  CONSTRAINT [CHK_EMPLEADO_SALARIO] CHECK  (([salario]>=(365)))
GO
ALTER TABLE [dbo].[empleados] CHECK CONSTRAINT [CHK_EMPLEADO_SALARIO]
GO
ALTER TABLE [dbo].[empleados]  WITH CHECK ADD  CONSTRAINT [CHK_EMPLEADO_TIPO_RENUNCIA] CHECK  (([TIPO_SALIDA]='R' OR [TIPO_SALIDA]='D'))
GO
ALTER TABLE [dbo].[empleados] CHECK CONSTRAINT [CHK_EMPLEADO_TIPO_RENUNCIA]
GO
ALTER TABLE [dbo].[HORAS_EXTRA]  WITH CHECK ADD  CONSTRAINT [CHK_DIURNA] CHECK  (([tipo]<>'O' OR [cantidad]<=(13)))
GO
ALTER TABLE [dbo].[HORAS_EXTRA] CHECK CONSTRAINT [CHK_DIURNA]
GO
ALTER TABLE [dbo].[HORAS_EXTRA]  WITH CHECK ADD  CONSTRAINT [CHK_NOCTURNA] CHECK  (([tipo]<>'E' OR [cantidad]<=(11)))
GO
ALTER TABLE [dbo].[HORAS_EXTRA] CHECK CONSTRAINT [CHK_NOCTURNA]
GO
ALTER TABLE [dbo].[HORAS_EXTRA]  WITH CHECK ADD  CONSTRAINT [CHK_Tipo_hora] CHECK  (([TIPO]='E' OR [TIPO]='O'))
GO
ALTER TABLE [dbo].[HORAS_EXTRA] CHECK CONSTRAINT [CHK_Tipo_hora]
GO
ALTER TABLE [dbo].[PRESTACIONES]  WITH CHECK ADD  CONSTRAINT [CHK_PRESTACION_ESTADO] CHECK  (([estado]='I' OR [estado]='A'))
GO
ALTER TABLE [dbo].[PRESTACIONES] CHECK CONSTRAINT [CHK_PRESTACION_ESTADO]
GO
ALTER TABLE [dbo].[PRESTACIONES]  WITH CHECK ADD  CONSTRAINT [CHK_TECHO_SUPERIOR_A_INFERIOR] CHECK  (([techo_superior]>[techo_inferior]))
GO
ALTER TABLE [dbo].[PRESTACIONES] CHECK CONSTRAINT [CHK_TECHO_SUPERIOR_A_INFERIOR]
GO
/****** Object:  StoredProcedure [dbo].[PROC_DE_HORAEX]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create proc [dbo].[PROC_DE_HORAEX](@id_horaEx int)
AS
	DELETE from HORAS_EXTRA  Where ID =  @id_horaEx
GO
/****** Object:  StoredProcedure [dbo].[PROC_DEL_AUSENCIAS]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- Crear procedimiento almacenado para eliminar ausencias

CREATE PROCEDURE [dbo].[PROC_DEL_AUSENCIAS]
    @id_ausencia INT
AS
BEGIN
    DELETE FROM [dbo].[AUSENCIAS]
        WHERE [id] = @id_ausencia;
END;

GO
/****** Object:  StoredProcedure [dbo].[PROC_DEL_DEPARTAMENTO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_DEL_DEPARTAMENTO]
    @id_depto INT
AS
BEGIN
    DELETE FROM [dbo].[departamentos]
        WHERE [id_depto] = @id_depto;
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_DEL_EMPLEADO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_DEL_EMPLEADO]
    @id_empleado INT
AS
BEGIN
    DELETE FROM [dbo].[empleados]
        WHERE [id_empleado] = @id_empleado;
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_DEL_PERMISO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_DEL_PERMISO]
    @id_permiso INT
AS
BEGIN
    DELETE FROM [dbo].[ROLES_MODULO]
        WHERE [id] = @id_permiso;
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_DEL_PRESTACION]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_DEL_PRESTACION]
    @id_prestacion INT
AS
BEGIN
    DELETE FROM [dbo].[PRESTACIONES]
        WHERE [id_prestacion] = @id_prestacion;
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_DEL_ROL]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- Crear procedimiento almacenado para eliminar roles

CREATE PROCEDURE [dbo].[PROC_DEL_ROL]
    @id_rol INT
AS
BEGIN
    DELETE FROM [dbo].[roles]
        WHERE [ID_ROL] = @id_rol;
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_DEL_USUARIO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- Crear procedimiento almacenado para eliminar usuario

CREATE PROCEDURE [dbo].[PROC_DEL_USUARIO]
    @id_usuario INT
AS
BEGIN
    DELETE FROM [dbo].[usuarios]
        WHERE [ID_USUARIO] = @id_usuario;
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_INS_UPD_AUSENCIAS]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_INS_UPD_AUSENCIAS]
    @id_ausencia INT,
    @fecha DATE,
    @id_empleado INT,
	@comentario NVARCHAR(500),
    @tipo CHAR(1) = NULL
AS
BEGIN
    SET NOCOUNT ON;

    IF EXISTS (SELECT * FROM [dbo].[AUSENCIAS] WHERE [id] = @id_ausencia)
        UPDATE [dbo].[AUSENCIAS]
        SET [fecha] = @fecha,
            [id_empleado] = @id_empleado,
            [comentario] = @comentario,
            [tipo] = @tipo
        WHERE [id] = @id_ausencia;
    ELSE
        INSERT INTO [dbo].[AUSENCIAS]
        ([fecha], [id_empleado], [comentario], [tipo])
        VALUES (@fecha, @id_empleado, @comentario, @tipo);
END;

GO
/****** Object:  StoredProcedure [dbo].[PROC_INS_UPD_DEPARTAMENTO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_INS_UPD_DEPARTAMENTO]
	@id_depto INT,
	@nombre nvarchar(100),
    @estado char(1)
AS
BEGIN
    SET NOCOUNT ON;

    IF EXISTS (SELECT * FROM [dbo].departamentos WHERE [id_depto] = @id_depto)
		UPDATE [dbo].departamentos 
		SET [nombre] = @nombre, 
			[estado] = @estado
		WHERE [id_depto] = @id_depto

    ELSE
		INSERT INTO departamentos
		([nombre], [estado]) 
        VALUES (@nombre, @estado);
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_INS_UPD_EMPLEADO]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_INS_UPD_EMPLEADO]
    @id_empleado INT,
    @nombres NVARCHAR(100),
    @apellidos NVARCHAR(100),
    @cargo NVARCHAR(100),
    @fecha_ingreso DATE,
    @fecha_salida DATE,
    @salario DECIMAL(10,0),
    @estado CHAR(1),
    @id_depto INT,
    @tipo_salida CHAR(1) = NULL,
    @estado_indem CHAR(1) = NULL,
    @id_tipo_contrato INT
AS
BEGIN
    SET NOCOUNT ON;

    IF EXISTS (SELECT * FROM [dbo].[empleados] WHERE [id_empleado] = @id_empleado)
        UPDATE [dbo].[empleados]
        SET [nombres] = @nombres,
            [apellidos] = @apellidos,
            [cargo] = @cargo,
            [fecha_ingreso] = @fecha_ingreso,
            [fecha_salida] = @fecha_salida,
            [salario] = @salario,
            [estado] = @estado,
            [id_depto] = @id_depto,
            [tipo_salida] = @tipo_salida,
            [estado_indem] = @estado_indem,
            [id_tipo_contrato] = @id_tipo_contrato
        WHERE [id_empleado] = @id_empleado;
    ELSE
        INSERT INTO [dbo].[empleados]
        ([nombres], [apellidos], [cargo], [fecha_ingreso], [fecha_salida], [salario], [estado], [id_depto], [tipo_salida], [estado_indem], [id_tipo_contrato])
        VALUES (@nombres, @apellidos, @cargo, @fecha_ingreso, @fecha_salida, @salario, @estado, @id_depto, @tipo_salida, @estado_indem, @id_tipo_contrato);
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_INS_UPD_HORAEXTRA]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROC [dbo].[PROC_INS_UPD_HORAEXTRA](@id_horasEx int, @fecha DATE,@tipo char(1), @cantidad int, @id_empleado int)
AS
    SET NOCOUNT ON;
    IF EXISTS (SELECT * FROM HORAS_EXTRA WHERE ID = @id_horasEx)
        UPDATE [dbo].[HORAS_EXTRA]
        SET FECHA = @fecha,
			TIPO = @tipo,
			CANTIDAD = @cantidad,
			ID_EMPLEADO = @id_empleado
        WHERE ID = @id_horasEx
    ELSE
        INSERT INTO [dbo].[HORAS_EXTRA]
        (FECHA, TIPO, CANTIDAD, ID_EMPLEADO)
        VALUES (@fecha, @tipo, @cantidad, @id_empleado)
GO
/****** Object:  StoredProcedure [dbo].[PROC_INS_UPD_PERMISOS]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_INS_UPD_PERMISOS]
	@id_permiso INT,
    @id_rol INT,
	@id_modulo INT,
    @estado_c CHAR(1),
	@estado_u CHAR(1),
	@estado_d CHAR(1),
	@estado_r CHAR(1)	
AS
BEGIN
    SET NOCOUNT ON;

    IF EXISTS (SELECT * FROM [dbo].ROLES_MODULO WHERE [id] = @id_permiso)
		UPDATE [dbo].ROLES_MODULO 
		SET [ID_ROL] = @id_rol, 
			[ID_MODULO] = @id_modulo,
			[PUEDE_INSERTAR] = @estado_c,
			[PUEDE_ACTUALIZAR] = @estado_u,
			[PUEDE_ELIMINAR] = @estado_d,
			[PUEDE_CONSULTAR]= @estado_r 
		WHERE [id] = @id_permiso

    ELSE
		INSERT INTO ROLES_MODULO
		([ID_ROL], [ID_MODULO], [PUEDE_INSERTAR], [PUEDE_ACTUALIZAR], [PUEDE_ELIMINAR], [PUEDE_CONSULTAR]) 
        VALUES (@id_rol, @id_modulo, @estado_c, @estado_u, @estado_d, @estado_r);
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_INS_UPD_PRESTACION]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_INS_UPD_PRESTACION]
    @id_prestacion INT,
    @nombre NVARCHAR(100),
    @porcentaje DECIMAL(5,2),
    @monto DECIMAL(17,2),
    @techo_inferior DECIMAL(17,2),
    @techo_superior DECIMAL(17,2),
    @estado CHAR(1)
AS
BEGIN
    SET NOCOUNT ON;

    IF EXISTS (SELECT * FROM [dbo].[PRESTACIONES] WHERE [id_prestacion] = @id_prestacion)
        UPDATE [dbo].[PRESTACIONES]
        SET [nombre] = @nombre,
            [porcentaje] = @porcentaje,
            [monto] = @monto,
            [techo_inferior] = @techo_inferior,
            [techo_superior] = @techo_superior,
            [estado] = @estado
        WHERE [id_prestacion] = @id_prestacion;
    ELSE
        INSERT INTO [dbo].[PRESTACIONES]
        ([nombre], [porcentaje], [monto], [techo_inferior], [techo_superior], [estado])
        VALUES (@nombre, @porcentaje, @monto, @techo_inferior, @techo_superior, @estado);
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_INS_UPD_ROLES]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_INS_UPD_ROLES]
    @id_rol INT,
	@nombre NVARCHAR(100),
	@estado CHAR(1) = NULL
AS
BEGIN
    SET NOCOUNT ON;

    IF EXISTS (SELECT * FROM [dbo].[roles] WHERE [ID_ROL] = @id_rol)
        UPDATE [dbo].[roles]
        SET [NOMBRE] = @nombre,
			[ESTADO] = @estado
        WHERE [ID_ROL] = @id_rol;
    ELSE
        INSERT INTO [dbo].[roles]
        ([NOMBRE], [ESTADO])
        VALUES (@nombre, @estado);
END;
GO
/****** Object:  StoredProcedure [dbo].[PROC_INS_UPD_USUARIOS]    Script Date: 11/4/2023 11:33:24 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[PROC_INS_UPD_USUARIOS]
    @id_usuario INT,
	@nombres NVARCHAR(100),
	@apellidos NVARCHAR(100),
	@email NVARCHAR(120),
	@password NVARCHAR(500),
    @id_rol INT,
	@imagen NVARCHAR(250),
	@id_empleado INT
AS
BEGIN
    SET NOCOUNT ON;

    IF EXISTS (SELECT * FROM [dbo].[usuarios] WHERE [ID_USUARIO] = @id_usuario)
        UPDATE [dbo].[usuarios]
        SET [NOMBRES] = @nombres,
            [APELLIDOS] = @apellidos,
            [EMAIL] = @email,
            [PASSWORD] = @password,
			[ID_ROL] = @id_rol,
			[IMAGEN] = @imagen,
			[id_empleado] = @id_empleado
        WHERE [ID_USUARIO] = @id_usuario;
    ELSE
        INSERT INTO [dbo].[usuarios]
        ([NOMBRES], [APELLIDOS], [EMAIL], [PASSWORD], [ID_ROL], [IMAGEN], [id_empleado])
        VALUES (@nombres, @apellidos, @email, @password, @id_rol, @imagen, @id_empleado);
END;
GO
/****** Object:  DdlTrigger [tr_seguridadTablas]    Script Date: 11/4/2023 11:33:25 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create trigger [tr_seguridadTablas]
on database
for alter_table, drop_table 
as
begin
	print 'Error! debe deshabilitar el trigger de seguridad para poder modificar y eliminar'
	rollback
end
GO
ENABLE TRIGGER [tr_seguridadTablas] ON DATABASE
GO
USE [master]
GO
ALTER DATABASE [rrhh] SET  READ_WRITE 
GO
