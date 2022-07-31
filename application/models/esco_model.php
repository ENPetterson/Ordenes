<?php
class Esco_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        if (defined('ENVIRONMENT')) {

            switch (ENVIRONMENT) {
                case 'javierdev':
 //case 'desajavier':
                    $this->dbh = new PDO ("sqlsrv:server=srv-vbolsa0;database=VBolsa","sa","25DeMayo");
                    $this->dbhTest = new PDO ("sqlsrv:server=srv-vbolsa0;database=test","sa","25DeMayo");
                    break;
                case 'allaria':
                    $this->dbh = new PDO("sqlsrv:Server=srv-vbolsa0.allaria.local;Database=VBolsa", "sa", "25DeMayo");
                    break;
                case 'desajavier':
                    $this->dbh = new PDO("dblib:host=srv-vbolsa0;dbname=VBolsa", "sa", "25DeMayo");
                    $this->dbhTest = new PDO("dblib:host=srv-vbolsa0;dbname=test", "sa", "25DeMayo");
                    break;
                case 'desamica':
                    $this->dbh = new PDO("dblib:host=srv-vbolsa0;dbname=VBolsa", "sa", "25DeMayo");
                    $this->dbhTest = new PDO("dblib:host=srv-vbolsa0;dbname=test", "sa", "25DeMayo");
                    break;
                case 'test':
                    $this->dbh = new PDO("sqlsrv:Server=srv-vbolsa0.allaria.local;Database=VBolsa", "sa", "25DeMayo");
//                    $this->dbh = new PDO("dblib:host=srv-vbolsa0;dbname=VBolsa", "sa", "25DeMayo");
                    $this->dbhTest = new PDO("dblib:host=srv-vbolsa0;dbname=test", "sa", "25DeMayo");
                    break;
                case 'andytest':
                    $this->dbh = new PDO("sqlsrv:server=172.20.6.11;Database=VBolsa;", "sa", "25DeMayo");
                    $this->dbhTest = new PDO("sqlsrv:server=172.20.6.11;Database=test;", "sa", "25DeMayo");
                    break;

                default:
                    exit('The application environment is not set correctly.');
            }
        }
    }

    private $dbh;
    public $numComitente;
    public $instrumento;
    public $fecha;

    
    
    
    
    
    public function getParkingComitenteInstrumento(){
        
        
        $numComitente = $this->numComitente;
        $especie = $this->especie;
        
        $fechaHastaHoy = (string) date('Y-m-d');
        
        if (!$this->fechaDesde) {
            $fechaDesde = (string) date('Ymd');
        } else {
            $fechaDesde = $this->fechaDesde;
            $fechaDesde = DateTime::createFromFormat('Y-m-d',$fechaDesde)->format('Ymd');
//            $fechaHasta = date('Ymd', strtotime($fechaDesde . ' +1 day'));
        }

        
        $tableId = (string) getmypid();        
                
        $sql = " select CodTabla, DescripcionTabla FROM appTABLASTEMP WHERE CodEntidad = 90 ORDER BY CodTabla
select CodTabla, NombreCampo, CodTpDato, EsNulable, EsIdentity, EsPrimaryKey  FROM appTABLASTEMPIT WHERE CodEntidad = 90 ORDER BY CodTabla
IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_CTACTE')) DROP TABLE #RPT_CTACTE
CREATE TABLE #RPT_CTACTE(CodCtaCorriente numeric(10,0) NULL , CodCtaCorrienteValor numeric(10,0) NULL , CodCtaCorrienteIt numeric(10,0) NULL , CodComitente numeric(10,0) NULL , EsCtaCorrientePap numeric(10,0) NULL , EsCtaCorrienteMon numeric(10,0) NULL , EsCtaCorrienteFdo numeric(10,0) NULL , TpItem varchar(80) COLLATE database_default  NULL , CodItem varchar(25) COLLATE database_default  NULL , Item varchar(80) COLLATE database_default  NULL , ItemAbrev varchar(30) COLLATE database_default  NULL , ItemCodigo numeric(15,0) NULL , CodInstrumento varchar(25) COLLATE database_default  NULL , CodigoInstrumento numeric(15,0) NULL , Instrumento varchar(80) COLLATE database_default  NULL , InstrumentoAbrev varchar(30) COLLATE database_default  NULL , InstrumentoTpEsp varchar(80) COLLATE database_default  NULL , DescripcionCtaCte varchar(300) COLLATE database_default  NULL , FechaOrden smalldatetime NULL , FechaConcertacion smalldatetime NULL , FechaLiquidacion smalldatetime NULL , Neto numeric(19,2) NULL , NetoSuma numeric(19,2) NULL , NetoSaldoDiario numeric(19,2) NULL , CantidadVN numeric(22,10) NULL , CantidadVNSuma numeric(22,10) NULL , CantidadVNSaldoDiario numeric(22,10) NULL , CantidadVR numeric(22,10) NULL , Saldo numeric(28,10) NULL , Origen varchar(25) COLLATE database_default  NULL , Clave1 numeric(10,0) NULL , Clave2 numeric(10,0) NULL , EsDatosVarios numeric(10,0) NULL , EsConformacionCuenta numeric(10,0) NULL , Bruto numeric(19,2) NULL , Moneda varchar(80) COLLATE database_default  NULL , MonedaSimb varchar(80) COLLATE database_default  NULL , CodMoneda numeric(5,0) NULL , CodFondo numeric(10,0) NULL , AgenteDepo varchar(80) COLLATE database_default  NULL , AgenteDepoDesc varchar(80) COLLATE database_default  NULL , CodEspecie numeric(10,0) NULL , CodIndice numeric(10,0) NULL , CodSerie numeric(10,0) NULL , CodSerieHist numeric(10,0) NULL , CodTpEspecie numeric(10,0) NULL , NumComitente numeric(15,0) NULL , Comitente varchar(150) COLLATE database_default  NULL , SubComitente varchar(80) COLLATE database_default  NULL , Numero numeric(12,0) NULL , CUIT varchar(80) COLLATE database_default  NULL , Plazo numeric(4,0) NULL , Precio numeric(19,10) NULL , Arancel numeric(19,2) NULL , ImpVNArancel numeric(19,10) NULL , PorcArancel numeric(12,8) NULL , PorcDerechoBls numeric(12,8) NULL , DerechoBls numeric(19,2) NULL , PorcDerechoMerc numeric(12,8) NULL , DerechoMerc numeric(19,2) NULL , TpOperacion varchar(80) COLLATE database_default  NULL , TpOperacionAlt varchar(80) COLLATE database_default  NULL , TpLiqMoneda varchar(80) COLLATE database_default  NULL , PorcComision numeric(12,8) NULL , Comision numeric(19,2) NULL , ImportePercIBCABA numeric(19,2) NULL , MonedaPercIBCABA varchar(80) COLLATE database_default  NULL , ImpIVADerechoMerc numeric(19,2) NULL , ImpIVAArancel numeric(19,2) NULL , ImpSTasaDerechoMerc numeric(19,2) NULL , ImpSTasaArancel numeric(19,2) NULL , PorcIVADerechoMerc numeric(12,8) NULL , PorcIVAArancel numeric(12,8) NULL , PorcSTasaDerechoMerc numeric(12,8) NULL , PorcSTasaArancel numeric(12,8) NULL , ImpIVAComision numeric(19,2) NULL , ImpSTasaComision numeric(19,2) NULL , PorcIVAComision numeric(12,8) NULL , PorcSTasaComision numeric(12,8) NULL , NumCtaCorrienteNDC numeric(15,0) NULL , NumCompFiscal numeric(15,0) NULL , EstaAnulado smallint NULL , EsDisponible numeric(10,0) NULL , Disponibilidad varchar(80) COLLATE database_default  NULL , FechaHasta smalldatetime NULL , FechaTotal smalldatetime NULL , EsSaldoAnterior smallint NULL , CodOficial numeric(10,0) NULL , Oficial varchar(300) COLLATE database_default  NULL , Of_EMail varchar(255) COLLATE database_default  NULL , CodAdmCartera numeric(10,0) NULL , AdmCartera varchar(300) COLLATE database_default  NULL , TpTransaccion varchar(80) COLLATE database_default  NULL , CodTpMovimiento varchar(2) COLLATE database_default  NULL , CodAsiento numeric(10,0) NULL , CodBoletoLiqMov numeric(10,0) NULL , CodTpCtaCorrienteND varchar(2) COLLATE database_default  NULL , CodCheque numeric(10,0) NULL , CodProductor numeric(10,0) NULL , Productor varchar(300) COLLATE database_default  NULL , CtaLiquidadora varchar(150) COLLATE database_default  NULL )
IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_MONEDAS')) DROP TABLE #RPT_MONEDAS
CREATE TABLE #RPT_MONEDAS(CodMoneda numeric(5,0) NULL , Descripcion varchar(80) COLLATE database_default  NULL , Simbolo varchar(30) COLLATE database_default  NULL )
IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_INSTRUMENTOS')) DROP TABLE #RPT_INSTRUMENTOS
CREATE TABLE #RPT_INSTRUMENTOS(CodEspecie numeric(10,0) NULL , CodTpEspecie numeric(5,0) NULL , CodSerie numeric(10,0) NULL , CodSerieHist numeric(10,0) NULL , CodIndice numeric(10,0) NULL , CodFondo numeric(10,0) NULL , CodigoInstrumento numeric(15,0) NULL , Descripcion varchar(80) COLLATE database_default  NULL , Abreviatura varchar(30) COLLATE database_default  NULL , CodInstrumento varchar(25) COLLATE database_default  NULL )
IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_COMITENTES')) DROP TABLE #RPT_COMITENTES
CREATE TABLE #RPT_COMITENTES(CodComitente numeric(10,0) NULL , Descripcion varchar(150) COLLATE database_default  NULL , NumComitente numeric(15,0) NULL , CodOficial numeric(10,0) NULL , Oficial varchar(300) COLLATE database_default  NULL , Of_EMail varchar(255) COLLATE database_default  NULL , CodAdmCartera numeric(10,0) NULL , AdmCartera varchar(300) COLLATE database_default  NULL , CodProductor numeric(10,0) NULL , Productor varchar(300) COLLATE database_default  NULL )
IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_AGENTESDEPO')) DROP TABLE #RPT_AGENTESDEPO
CREATE TABLE #RPT_AGENTESDEPO(CodDepositario numeric(5,0) NULL , CodAgenteDepo numeric(3,0) NULL , Descripcion varchar(80) COLLATE database_default  NULL , NumCuenta varchar(30) COLLATE database_default  NULL )
IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_CTACTE_MOV')) DROP TABLE #RPT_CTACTE_MOV
CREATE TABLE #RPT_CTACTE_MOV(CodCtaCorriente numeric(10,0) NULL , CodCtaCorrientePap numeric(10,0) NULL , CodCtaCorrienteMon numeric(10,0) NULL , CodCtaCorrientePapND numeric(10,0) NULL , CodCtaCorrienteMonND numeric(10,0) NULL , CodBoleto numeric(10,0) NULL , CodBoletoLiqMov numeric(10,0) NULL , CodComitente numeric(10,0) NULL , Origen varchar(25) COLLATE database_default  NULL , FechaConcertacion smalldatetime NULL , FechaLiquidacion smalldatetime NULL , Descripcion varchar(300) COLLATE database_default  NULL , EstaAnulado smallint NULL )
IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_CTACTE_TMP')) DROP TABLE #RPT_CTACTE_TMP
CREATE TABLE #RPT_CTACTE_TMP(CodCtaCorriente numeric(10,0) NULL , CodComitente numeric(10,0) NULL )
INSERT #RPT_COMITENTES (CodComitente ,Descripcion, NumComitente ,CodOficial , Oficial, Of_EMail, CodAdmCartera, AdmCartera, CodProductor, Productor)  select  COMITENTES.CodComitente ,  COMITENTES.Descripcion, COMITENTES.NumComitente ,OFICIAL.CodOperativo , OFICIAL.Apellido + ', ' + OFICIAL.Nombre, OFICIAL.EMail, ADCARTERA.CodOperativo , ADCARTERA.Apellido + ', ' + ADCARTERA.Nombre, PRODUCTOR.CodOperativo , PRODUCTOR.Apellido + ', ' + PRODUCTOR.Nombre FROM  COMITENTES  LEFT JOIN TPCOMITENTE ON COMITENTES.CodTpComitente = TPCOMITENTE.CodTpComitente LEFT JOIN SUCURSALES ON COMITENTES.CodSucursal = SUCURSALES.CodSucursal LEFT JOIN OPERATIVOSROLCMT OFICIALROL INNER JOIN OPERATIVOS OFICIAL ON OFICIAL.CodOperativo = OFICIALROL.CodOperativo AND OFICIALROL.CodRol = 'OC'  ON OFICIALROL.CodComitente = COMITENTES.CodComitente  LEFT JOIN OPERATIVOSROLCMT ADCARTERAROL INNER JOIN OPERATIVOS ADCARTERA ON ADCARTERA.CodOperativo = ADCARTERAROL.CodOperativo AND ADCARTERAROL.CodRol = 'AC'  ON ADCARTERAROL.CodComitente = COMITENTES.CodComitente  LEFT JOIN OPERATIVOSROLCMT PRODUCTORROL INNER JOIN OPERATIVOS PRODUCTOR ON PRODUCTOR.CodOperativo = PRODUCTORROL.CodOperativo AND PRODUCTORROL.CodRol = 'PR'  ON PRODUCTORROL.CodComitente = COMITENTES.CodComitente  WHERE ((COMITENTES.NumComitente = $numComitente))
CREATE CLUSTERED INDEX [XINDCMT] ON #RPT_COMITENTES (CodComitente)
INSERT #RPT_INSTRUMENTOS (CodEspecie ,CodTpEspecie, Abreviatura ,CodSerie ,CodSerieHist ,CodIndice ,Descripcion, CodigoInstrumento ,CodInstrumento , CodFondo)   select CodEspecie ,INSTRUMENTOS.CodTpEspecie, INSTRUMENTOS.Abreviatura , CodSerie ,CodSerieHist ,CodIndice ,INSTRUMENTOS.Descripcion, Codigo ,CodInstrumento, CodFondo FROM vwInstrumentos INSTRUMENTOS LEFT JOIN TPESPECIE ON TPESPECIE.CodTpEspecie = INSTRUMENTOS.CodTpEspecie WHERE  ((DATEDIFF (day, '$fechaDesde', INSTRUMENTOS.FVencimiento) >= 0 OR INSTRUMENTOS.FVencimiento IS NULL)) AND ((UPPER(INSTRUMENTOS.Abreviatura) LIKE UPPER('$especie') ))
INSERT #RPT_AGENTESDEPO (CodDepositario, CodAgenteDepo , Descripcion ,NumCuenta)   select AGENTESDEPO.CodDepositario, AGENTESDEPO.CodAgenteDepo , DEPOSITARIOS.Descripcion , AGENTESDEPO.NumCuenta FROM AGENTESDEPO INNER JOIN DEPOSITARIOS ON DEPOSITARIOS.CodDepositario = AGENTESDEPO.CodDepositario 
CREATE CLUSTERED INDEX [XINDINSTR] ON #RPT_INSTRUMENTOS (CodInstrumento)
CREATE CLUSTERED INDEX [XINDCC] ON #RPT_CTACTE (CodCtaCorriente,CodCtaCorrienteValor)
exec spINFO_CTACTEPAP @PorConcertacion=0,@IncluirAnulados=0,@FechaDesde='$fechaDesde',@FechaHasta=NULL
exec spINFO_CTACTEFDO @PorConcertacion=0,@IncluirAnulados=0,@FechaDesde='$fechaDesde',@FechaHasta=NULL
exec spINFO_CTACTEPAPND @PorConcertacion=0,@IncluirAnulados=0,@FechaDesde='$fechaDesde',@FechaHasta=NULL
exec spINFO_CTACTEFDOND @PorConcertacion=0,@IncluirAnulados=0,@FechaDesde='$fechaDesde',@FechaHasta=NULL
exec spINFO_CTACTEACTDATOS_PAP 
exec spINFO_CTACTEPAPSA @PorConcertacion=0,@FechaDesde='$fechaDesde',@FechaHasta=NULL,@SinDepositario=0
exec spINFO_CTACTEFDOSA @PorConcertacion=0,@FechaDesde='$fechaDesde',@FechaHasta=NULL,@SinDepositario=0
exec spINFO_CTACTEPAPNDSA @PorConcertacion=0,@FechaDesde='$fechaDesde',@FechaHasta=NULL,@SinDepositario=0
exec spINFO_CTACTEFDONDSA @PorConcertacion=0,@FechaDesde='$fechaDesde',@FechaHasta=NULL,@SinDepositario=0
exec spINFO_CTACTEACTDATOS_VARIOS @PorConcertacion=0,@FechaHasta='$fechaHastaHoy',@CodLenguaje='ESPARG'
exec spINFO_CTACTESALDOACUM @FechaHasta='$fechaHastaHoy',@InformaComercialHist=0,@ExcluirCeros=0
exec spINFO_SacarComOfCta @CodParametroEnt='COMOFCTA',@CodEntidad=674
exec sp_appCENTCONFIGURACIONUSER @CodAccion='ENTCONFIGUSERd',@CodUsuario=391,@CodEntidad=674
select ConLineasH, ConLineasV, NumeraHojas FROM appENTCONFIGURACIONUSER WHERE CodEntidad = 674 AND CodUsuario = 391 AND CodEntConfiguracionUser = 'Configuración Inicial'
select COALESCE(EsImpresionHoriz,-1) EsImpresionHoriz  FROM appENTIDADES WHERE CodEntidad = 674
select SentenciaSQL, COALESCE(PermiteModificarCol,-1) PermiteModificarCol, COALESCE(PermiteModificarOrd,-1) PermiteModificarOrd, COALESCE(PermiteModificarFn,-1) PermiteModificarFn, COALESCE(PermiteModificarCrt,-1) PermiteModificarCrt, COALESCE(FntColSize,8) FntColSize, COALESCE(FntColName,'Arial') FntColName, COALESCE(FntEncName,'Arial') FntEncName, COALESCE(FntEncSize,8) FntEncSize , COALESCE(FntEncBold,-1) FntEncBold, EsMultiLenguaje FROM appENTIDADES WHERE CodEntidad = 674
exec sp_appCENTCOLUMNASUSER @CodEntConfiguracionUser='Configuración Inicial',@CodUsuario=391,@CodEntidad=674
exec sp_appCENTCORTESUSER @CodEntConfiguracionUser='Configuración Inicial',@CodUsuario=391,@CodEntidad=674
exec sp_appCENTFUNCIONESUSER @CodEntConfiguracionUser='Configuración Inicial',@CodUsuario=391,@CodEntidad=674
IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#GRILLA_$tableId')) DROP TABLE #GRILLA_$tableId
CREATE TABLE #GRILLA_$tableId (FechaLiquidacion smalldatetime NULL , TpTransaccion varchar(80) COLLATE database_default  NULL , InstrumentoAbrev varchar(80) COLLATE database_default  NULL , CantidadVN numeric(22,10) NULL , Saldo numeric(19,2) NULL , Neto numeric(19,2) NULL , DescripcionCtaCte varchar(300) COLLATE database_default  NULL , Numero numeric(12,0) NULL , NumComitente numeric(15,0) NULL , Comitente varchar(150) COLLATE database_default  NULL , TpItem varchar(80) COLLATE database_default  NULL , FechaOrden smalldatetime NULL , Item varchar(80) COLLATE database_default  NULL , ItemAbrev varchar(80) COLLATE database_default  NULL , CodCtaCorrienteIt numeric(10,0) NULL , CodigoInstrumento numeric(15,0) NULL , CodInstrumento varchar(25) COLLATE database_default  NULL , CodItem varchar(25) COLLATE database_default  NULL , CodMoneda numeric(10,0) NULL , CantidadVNSaldoDiario numeric(22,10) NULL , CantidadVNSuma numeric(22,10) NULL , Clave1 numeric(10,0) NULL , Clave2 numeric(10,0) NULL , CodComitente numeric(10,0) NULL , DerechoBls numeric(19,2) NULL , DerechoMerc numeric(19,2) NULL , AgenteDepo varchar(80) COLLATE database_default  NULL , Arancel numeric(19,2) NULL , Bruto numeric(19,2) NULL , Disponibilidad varchar(80) COLLATE database_default  NULL , EsDisponible smallint NULL , EsSaldoAnterior smallint NULL , EstaAnulado smallint NULL , FechaConcertacion smalldatetime NULL , FechaHasta smalldatetime NULL , ItemCodigo varchar(15) COLLATE database_default  NULL , Moneda varchar(80) COLLATE database_default  NULL , MonedaSimb varchar(30) COLLATE database_default  NULL , NumCompFiscal numeric(15,0) NULL , NetoSaldoDiario numeric(19,2) NULL , NetoSuma numeric(19,2) NULL , FechaTotal smalldatetime NULL , FMT_GRILLA varchar(80) COLLATE database_default  NULL , ImpIVAArancel numeric(19,2) NULL , ImpIVADerechoMerc numeric(19,2) NULL , ImpSTasaArancel numeric(19,2) NULL , ImpSTasaDerechoMerc numeric(19,2) NULL , ImpVNArancel numeric(19,10) NULL , Instrumento varchar(80) COLLATE database_default  NULL , TpLiqMoneda varchar(80) COLLATE database_default  NULL , TpOperacion varchar(80) COLLATE database_default  NULL , Oficial varchar(300) COLLATE database_default  NULL , Origen varchar(25) COLLATE database_default  NULL , Plazo numeric(4,0) NULL , PorcArancel numeric(12,8) NULL , PorcDerechoBls numeric(12,8) NULL , PorcDerechoMerc numeric(19,2) NULL , PorcIVAArancel numeric(12,8) NULL , PorcIVADerechoMerc numeric(12,8) NULL , PorcSTasaArancel numeric(12,8) NULL , PorcSTasaDerechoMerc numeric(12,8) NULL , Precio numeric(19,10) NULL , IDPosicion numeric(10,0) IDENTITY(1, 1) NOT NULL , DFPaginacion numeric(10,0) NULL  PRIMARY KEY (IDPosicion))
 INSERT #GRILLA_$tableId  select #RPT_CTACTE.FechaLiquidacion, COALESCE(#RPT_CTACTE.TpTransaccion, #RPT_CTACTE.TpOperacion), #RPT_CTACTE.InstrumentoAbrev, #RPT_CTACTE.CantidadVN, #RPT_CTACTE.Saldo, #RPT_CTACTE.Neto, #RPT_CTACTE.DescripcionCtaCte, COALESCE(#RPT_CTACTE.Numero, #RPT_CTACTE.NumCtaCorrienteNDC, NULL), #RPT_CTACTE.NumComitente, #RPT_CTACTE.Comitente, #RPT_CTACTE.TpItem, #RPT_CTACTE.FechaOrden, #RPT_CTACTE.Item, #RPT_CTACTE.ItemAbrev, #RPT_CTACTE.CodCtaCorrienteIt, #RPT_CTACTE.CodigoInstrumento, #RPT_CTACTE.CodInstrumento, #RPT_CTACTE.CodItem, #RPT_CTACTE.CodMoneda, #RPT_CTACTE.CantidadVNSaldoDiario, #RPT_CTACTE.CantidadVNSuma, #RPT_CTACTE.Clave1, #RPT_CTACTE.Clave2, #RPT_CTACTE.CodComitente, #RPT_CTACTE.DerechoBls, #RPT_CTACTE.DerechoMerc, #RPT_CTACTE.AgenteDepo, #RPT_CTACTE.Arancel, #RPT_CTACTE.Bruto, #RPT_CTACTE.Disponibilidad, #RPT_CTACTE.EsDisponible, #RPT_CTACTE.EsSaldoAnterior, #RPT_CTACTE.EstaAnulado, #RPT_CTACTE.FechaConcertacion, #RPT_CTACTE.FechaHasta, ISNULL(CONVERT(VARCHAR(15), #RPT_CTACTE.ItemCodigo), ''), #RPT_CTACTE.Moneda, #RPT_CTACTE.MonedaSimb, #RPT_CTACTE.NumCompFiscal, #RPT_CTACTE.NetoSaldoDiario, #RPT_CTACTE.NetoSuma, #RPT_CTACTE.FechaTotal, 'Cuentas Corrientes Estándar (Instrumentos)', #RPT_CTACTE.ImpIVAArancel, #RPT_CTACTE.ImpIVADerechoMerc, #RPT_CTACTE.ImpSTasaArancel, #RPT_CTACTE.ImpSTasaDerechoMerc, #RPT_CTACTE.ImpVNArancel, #RPT_CTACTE.Instrumento, #RPT_CTACTE.TpLiqMoneda, #RPT_CTACTE.TpOperacion, #RPT_CTACTE.Oficial, #RPT_CTACTE.Origen, #RPT_CTACTE.Plazo, #RPT_CTACTE.PorcArancel, #RPT_CTACTE.PorcDerechoBls, #RPT_CTACTE.PorcDerechoMerc, #RPT_CTACTE.PorcIVAArancel, #RPT_CTACTE.PorcIVADerechoMerc, #RPT_CTACTE.PorcSTasaArancel, #RPT_CTACTE.PorcSTasaDerechoMerc, #RPT_CTACTE.Precio,  0 FROM #RPT_CTACTE
 select Count(*) as Cantidad FROM #GRILLA_$tableId
 select EstaAnulado FROM #GRILLA_$tableId ORDER BY IDPosicion
UPDATE #GRILLA_$tableId SET DFPaginacion = 0
SELECT TOP 1 COUNT(IDPosicion) CantidadReg FROM #GRILLA_$tableId GROUP BY #GRILLA_$tableId.FMT_GRILLA,#GRILLA_$tableId.CodComitente,#GRILLA_$tableId.NumComitente,#GRILLA_$tableId.Comitente,#GRILLA_$tableId.Oficial,#GRILLA_$tableId.EsDisponible,#GRILLA_$tableId.Disponibilidad,#GRILLA_$tableId.TpItem,#GRILLA_$tableId.CodItem,#GRILLA_$tableId.Item,#GRILLA_$tableId.ItemAbrev,#GRILLA_$tableId.ItemCodigo,#GRILLA_$tableId.FechaTotal HAVING COUNT(IDPosicion)>200
 select DISTINCT #GRILLA_$tableId.FMT_GRILLA,#GRILLA_$tableId.CodComitente,#GRILLA_$tableId.NumComitente,#GRILLA_$tableId.Comitente,#GRILLA_$tableId.Oficial,#GRILLA_$tableId.EsDisponible,#GRILLA_$tableId.Disponibilidad,#GRILLA_$tableId.TpItem,#GRILLA_$tableId.CodItem,#GRILLA_$tableId.Item,#GRILLA_$tableId.ItemAbrev,#GRILLA_$tableId.ItemCodigo,#GRILLA_$tableId.FechaTotal FROM #GRILLA_$tableId ORDER BY  #GRILLA_$tableId.FMT_GRILLA ASC, #GRILLA_$tableId.NumComitente ASC, #GRILLA_$tableId.Comitente ASC, #GRILLA_$tableId.CodComitente ASC, #GRILLA_$tableId.Oficial ASC, #GRILLA_$tableId.EsDisponible ASC, #GRILLA_$tableId.TpItem ASC, #GRILLA_$tableId.Item ASC, #GRILLA_$tableId.ItemAbrev ASC, #GRILLA_$tableId.CodItem ASC, #GRILLA_$tableId.FechaTotal ASC
 select FechaLiquidacion,TpTransaccion,InstrumentoAbrev,CantidadVN,Saldo,Neto,DescripcionCtaCte,Numero,EsSaldoAnterior,IDPosicion FROM #GRILLA_$tableId WHERE FMT_GRILLA = 'Cuentas Corrientes Estándar (Instrumentos)' AND CodComitente = 10600 AND Oficial = 'DONADEU, CARLOS' AND EsDisponible = -1 AND TpItem = 'Instrumentos' AND CodItem = 'E22844' AND DATEDIFF(day,FechaTotal,'$fechaDesde')=0 ORDER BY EsSaldoAnterior,NumComitente ASC,Comitente ASC,TpItem ASC,Item ASC,ItemAbrev ASC,FechaOrden ASC,CodCtaCorrienteIt ASC

        ";
        
        
        
        file_put_contents('/var/www/operaciones/test.sql', $sql);
        $instrucciones = explode("\n", $sql);
        $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
        foreach ($instrucciones as $instruccion){
            try {
                $this->dbh->exec($instruccion);
            } catch (PDOException $e){
                echo $e->getMessage();
                die();
            }
        }
                
//        $sql2=  "SELECT FMT_GRILLA FROM test.dbo.Grilla_$tableId";
//        $sql2=  "SELECT FMT_GRILLA FROM #GRILLA_$tableId ORDER BY FechaLiquidacion";
        
        
//$sql2=  "SELECT * FROM #GRILLA_$tableId";
$sql2 =  "SELECT FechaLiquidacion, TpTransaccion, CantidadVN, EsDisponible, Numero, DescripcionCtaCte FROM #GRILLA_$tableId ORDER BY FechaLiquidacion ASC";
        
        $stmt = $this->dbh->prepare($sql2);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
        
        unset($stmt);
        if ($results){
            return $this->utf8_converter($results);
        } else {
            return false;
        }

    }
    
    
    public function grilla(){
        
        
        
        //Esta es la query unificada de tomadora y colocadora.
        /*
         * 
         * 
         USE VBolsa
        GO

        SELECT  COMITENTES.NumComitente as NumComitente,
        COMITENTES.Descripcion as Comitente,

        ( select SUM(DISTRIBUCIONESPACA.MontoCont * CASE WHEN TPOPERACIONDISTRPACA.CodTpOperacionMinuPaCa = 'C' THEN -1 ELSE 1 END) WHERE SUM(DISTRIBUCIONESPACA.MontoCont * CASE
                WHEN TPOPERACIONDISTRPACA.CodTpOperacionMinuPaCa = 'C' THEN -1
                ELSE 1
                END) > 0 ) as Colocadora,

        ( select SUM(DISTRIBUCIONESPACA.MontoCont * CASE WHEN TPOPERACIONDISTRPACA.CodTpOperacionMinuPaCa = 'C' THEN -1 ELSE 1 END) WHERE SUM(DISTRIBUCIONESPACA.MontoCont * CASE
                WHEN TPOPERACIONDISTRPACA.CodTpOperacionMinuPaCa = 'C' THEN -1
                ELSE 1
                END) < 0 ) as Tomadora


        FROM        COMITENTES

        INNER JOIN DISTRIBUCIONESPACA
        ON         DISTRIBUCIONESPACA.CodComitente = COMITENTES.CodComitente

        INNER JOIN TPOPERACIONDISTRPACA
                ON         TPOPERACIONDISTRPACA.CodTpOperacionDistrPaCa = DISTRIBUCIONESPACA.CodTpOperacionDistrPaCa

        WHERE DISTRIBUCIONESPACA.EstaAnulado = 0
                AND   convert(varchar(20), DISTRIBUCIONESPACA.FechaConcertacion, 112) = CONVERT(varchar(20), getdate(), 112)
                AND DISTRIBUCIONESPACA.CodMoneda = 1
                GROUP BY COMITENTES.NumComitente, COMITENTES.Descripcion, DISTRIBUCIONESPACA.CodMoneda
                ORDER BY COMITENTES.NumComitente

        GO


         * 
         * 
         * 
         * 
         *          
         
         * 
         * 
         * 
         */
        
        
        
        
        
        
        
                        
        //Colocadora    
        $sql1 = "select COMITENTES.NumComitente as NumComitente,
                        COMITENTES.Descripcion as Comitente, 
                        DISTRIBUCIONESPACA.CodMoneda as CodMoneda,
                        0 as Tomadora,
                        SUM(DISTRIBUCIONESPACA.MontoCont * CASE 
                            WHEN TPOPERACIONDISTRPACA.CodTpOperacionMinuPaCa = 'C' THEN -1 
                            ELSE 1 
                        END) as Colocadora,
                        0 as Inmediato,
                        0 as SaldoDisponible,
                        0 as SaldoNoDisponible,
                        0 as SaldoTotal
                FROM        DISTRIBUCIONESPACA 
                INNER JOIN    TPOPERACIONDISTRPACA 
                ON            DISTRIBUCIONESPACA.CodTpOperacionDistrPaCa    = TPOPERACIONDISTRPACA.CodTpOperacionDistrPaCa 
                AND            TPOPERACIONDISTRPACA.ConCheque                = 0 
                INNER JOIN    COMITENTES   
                ON            DISTRIBUCIONESPACA.CodComitente                = COMITENTES.CodComitente 
                WHERE DISTRIBUCIONESPACA.EstaAnulado = 0
                AND            convert(varchar(20), DISTRIBUCIONESPACA.FechaConcertacion, 112) = CONVERT(varchar(20), getdate(), 112)
                AND DISTRIBUCIONESPACA.CodMoneda = 1
                GROUP BY COMITENTES.NumComitente, COMITENTES.Descripcion, DISTRIBUCIONESPACA.CodMoneda
                HAVING SUM(DISTRIBUCIONESPACA.MontoCont * CASE 
                            WHEN TPOPERACIONDISTRPACA.CodTpOperacionMinuPaCa = 'C' THEN -1 
                            ELSE 1 
                        END) > 0
                ORDER BY COMITENTES.NumComitente";

        $stmt1 = $this->dbh->prepare($sql1);
        $stmt1->execute();
        $results1=$stmt1->fetchAll(PDO::FETCH_ASSOC);
        
        //tomadora
        $sql2 = "select COMITENTES.NumComitente as NumComitente,
                        COMITENTES.Descripcion as Comitente, 
                        DISTRIBUCIONESPACA.CodMoneda as CodMoneda,
                        SUM(DISTRIBUCIONESPACA.MontoCont * CASE 
                            WHEN TPOPERACIONDISTRPACA.CodTpOperacionMinuPaCa = 'C' THEN -1 
                            ELSE 1 
                        END) as Tomadora,
                        0 as Colocadora,
                        0 as Inmediato,
                        0 as SaldoDisponible,
                        0 as SaldoNoDisponible,
                        0 as SaldoTotal
                FROM        DISTRIBUCIONESPACA 
                INNER JOIN    TPOPERACIONDISTRPACA 
                ON            DISTRIBUCIONESPACA.CodTpOperacionDistrPaCa    = TPOPERACIONDISTRPACA.CodTpOperacionDistrPaCa 
                AND            TPOPERACIONDISTRPACA.ConCheque                = 0 
                INNER JOIN    COMITENTES   
                ON            DISTRIBUCIONESPACA.CodComitente                = COMITENTES.CodComitente 
                WHERE DISTRIBUCIONESPACA.EstaAnulado = 0
                AND DISTRIBUCIONESPACA.CodMoneda = 1
                AND convert(varchar(20), DISTRIBUCIONESPACA.FechaConcertacion, 112) = CONVERT(varchar(20), getdate(), 112)
                GROUP BY COMITENTES.NumComitente, COMITENTES.Descripcion, DISTRIBUCIONESPACA.CodMoneda
                HAVING SUM(DISTRIBUCIONESPACA.MontoCont * CASE 
                            WHEN TPOPERACIONDISTRPACA.CodTpOperacionMinuPaCa = 'C' THEN -1 
                            ELSE 1 
                        END) < 0
                ORDER BY COMITENTES.NumComitente";

        $stmt2 = $this->dbh->prepare($sql2);
        $stmt2->execute();
        $results2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        //inmediato
        $sql3 = "select COMITENTES.NumComitente as NumComitente, 
                        min(COMITENTES.Descripcion) as Comitente, 
                        MONEDASAR.CodMoneda as CodMoneda,
                        0 as Tomadora,
                        0 as Colocadora,
                 
                        sum(dbo.fnCantPorPrecioRedondeadoMinBol(MONEDASOPER.CodIDMoneda, MINUTAS.CodTpOperacionBurs, AGENTES.LiquidaMerc, #MINUTASASIG.Cantidad, MINUTAS.Precio, MINUTAS.CantAccionesLt) * CASE WHEN TPOPERMINU.EsDebito = -1 THEN -1 ELSE 1 END) as Inmediato,

                        0 as SaldoDisponible,
                        0 as SaldoNoDisponible,
                        0 as SaldoTotal

        FROM MINUTASASIG   INNER JOIN (select MINUTASASIG.CodMinutaAsig, tblMINUTAS.CodMinuta, MINUTASASIG.CodComitente, MINUTASASIG.CodMonedaCo, MINUTASASIG.CodAgente AS CodAgenteCo, MINUTASASIG.CodOperativo, MINUTASASIG.CodLibroOperativo, tblMINUTAS.CodOperador, MINUTASASIG.CodOrdenBurs, MINUTASASIG.CodSubComitente, MINUTASASIG.EstaAnulado, MINUTASASIG.TpCambioMinuLiq, MINUTASASIG.TpCambioArMinu, MINUTASASIG.TpCambioDerMinu, MINUTASASIG.TpCambioMinuPais, MINUTASASIG.Cantidad, MINUTASASIG.PorcArancel, MINUTASASIG.ImpVNArancel, MINUTASASIG.NumRelacion, MINUTASASIG.PrecioRef, MINUTASASIG.EsAutomatica, MINUTASASIG.ConBonificacionDer, MINUTASASIG.CodTrading, MINUTASASIG.PorcComOperativo, MINUTASASIG.ModInterfaz, MINUTASASIG.EsFTF, MINUTASASIG.Observaciones, MINUTASASIG.ImporteCo, MINUTASASIG.CodMonedaOper, MINUTASASIG.CodMonedaAr, MINUTASASIG.CodMonedaDer, MINUTASASIG.CodAuditoriaRef, MINUTASASIG.HoraOrden  FROM MINUTASASIG INNER JOIN (select MINUTAS.CodMinuta, MINUTAS.CodTpOperacionBurs, MINUTAS.Cantidad, MINUTAS.Precio, MINUTAS.HoraCarga, MINUTAS.HoraOferta, MINUTAS.FechaConcertacion, MINUTAS.Plazo, MINUTAS.FechaLiquidacion, MINUTAS.NumRegistro, MINUTAS.CantAsignada, MINUTAS.NumMinuta, MINUTAS.ConArancelExtra, MINUTAS.CodAgenteDepo, MINUTAS.CodEspecie, MINUTAS.CodSerie, MINUTAS.CodSerieHist, MINUTAS.CodIndice, MINUTAS.CodAgente, MINUTAS.CodOperador, MINUTAS.CodCtaContableDVP, CTASCONTABLES.Descripcion as DescripcionDVP,SERIESHIST.CantAccionesLt FROM   MINUTAS INNER JOIN (select INSTRUMENTOS.Codigo, CodEspecie ,INSTRUMENTOS.CodTpEspecie, INSTRUMENTOS.Abreviatura , CodSerie ,CodSerieHist ,CodIndice ,INSTRUMENTOS.Descripcion, Codigo as CodigoInstrumento,CodInstrumento, CodFondo FROM vwInstrumentos INSTRUMENTOS LEFT JOIN TPESPECIE ON TPESPECIE.CodTpEspecie = INSTRUMENTOS.CodTpEspecie) INSTRUMENTOS ON MINUTAS.CodEspecie = INSTRUMENTOS.CodEspecie AND COALESCE(MINUTAS.CodSerie, -1) = COALESCE(INSTRUMENTOS.CodSerie, -1) AND COALESCE(MINUTAS.CodSerieHist, -1) = COALESCE(INSTRUMENTOS.CodSerieHist, -1) AND COALESCE(MINUTAS.CodIndice, -1) = COALESCE(INSTRUMENTOS.CodIndice, -1) INNER JOIN TPOPERACIONBURS TPOPERMINU ON MINUTAS.CodTpOperacionBurs = TPOPERMINU.CodTpOperacionBurs INNER JOIN AGENTES ON MINUTAS.CodAgente = AGENTES.CodAgente LEFT JOIN SERIESHIST on SERIESHIST.CodEspecie = MINUTAS.CodEspecie AND SERIESHIST.CodSerie = MINUTAS.CodSerie AND SERIESHIST.EstaAnulado = 0 AND SERIESHIST.FechaDesde = (SELECT MAX(ESH2.FechaDesde) FROM SERIESHIST ESH2 WHERE MINUTAS.CodTpOperacionBurs IN ('OPCL', 'OPVL', 'OPCT', 'OPVT') AND ESH2.CodEspecie = MINUTAS.CodEspecie AND ESH2.CodSerie = MINUTAS.CodSerie AND ESH2.EstaAnulado = 0 AND ESH2.FechaDesde <= MINUTAS.FechaConcertacion) INNER JOIN AGENTESDEPO INNER JOIN DEPOSITARIOS ON AGENTESDEPO.CodDepositario = DEPOSITARIOS.CodDepositario ON MINUTAS.CodAgenteDepo = AGENTESDEPO.CodAgenteDepo LEFT JOIN OPERADORES ON MINUTAS.CodOperador = OPERADORES.CodOperador LEFT JOIN CTASCONTABLES WITH(INDEX=XPKCTASCONTABLES) ON MINUTAS.CodCtaContableDVP = CTASCONTABLES.CodCtaContable WHERE ((MINUTAS.FechaConcertacion >= CONVERT(VARCHAR,GETDATE(),112) AND MINUTAS.FechaConcertacion <= CONVERT(VARCHAR, GETDATE(),112))) AND ((MINUTAS.FechaLiquidacion >= CONVERT(VARCHAR, GETDATE(), 112) AND MINUTAS.FechaLiquidacion <= CONVERT(VARCHAR, GETDATE(),112)))) tblMINUTAS ON MINUTASASIG.CodMinuta = tblMINUTAS.CodMinuta) AS #MINUTASASIG on MINUTASASIG.CodMinutaAsig = #MINUTASASIG.CodMinutaAsig   
        LEFT JOIN MINUTASASIGCNT WITH(INDEX=XIND2MINUTASASIGCNT) ON MINUTASASIGCNT.CodMinutaAsig = #MINUTASASIG.CodMinutaAsig AND MINUTASASIGCNT.EstaAnulado = 0   
        LEFT JOIN MINUTASBOL      INNER JOIN BOLETOS         INNER JOIN TPOPERACIONBURS TPOPERBOL ON BOLETOS.CodTpOperacionBurs = TPOPERBOL.CodTpOperacionBurs AND COALESCE(TPOPERBOL.TpBoleto, 'BC') = 'BC'      ON BOLETOS.CodBoleto = MINUTASBOL.CodBoleto AND BOLETOS.EstaAnulado = 0   ON MINUTASBOL.CodMinutaAsig = #MINUTASASIG.CodMinutaAsig   
        INNER JOIN (select COMITENTES.CodComitente, COMITENTES.Descripcion, COMITENTES.NumComitente, COMITENTES.CodTpComitente, COMITENTES.CodTpCmtTrading FROM COMITENTES WITH(INDEX=XPKCOMITENTES) LEFT JOIN TPCOMITENTE on TPCOMITENTE.CodTpComitente = COMITENTES.CodTpComitente LEFT JOIN TPCMTTRADING on TPCMTTRADING.CodTpCmtTrading = COMITENTES.CodTpCmtTrading) COMITENTES ON COMITENTES.CodComitente = #MINUTASASIG.CodComitente       
        LEFT JOIN OPERATIVOSROLCMT OFICIALROL           INNER JOIN OPERATIVOS OFICIAL ON OFICIALROL.CodOperativo = OFICIAL.CodOperativo         ON COMITENTES.CodComitente = OFICIALROL.CodComitente AND OFICIALROL.CodRol = 'OC'       LEFT JOIN OPERATIVOSROLCMT REFERENTEROL           INNER JOIN OPERATIVOS REFERENTE ON REFERENTEROL.CodOperativo = REFERENTE.CodOperativo         ON COMITENTES.CodComitente = REFERENTEROL.CodComitente AND REFERENTEROL.CodRol = 'RE'       LEFT JOIN OPERATIVOSROLCMT ADMINCARTERAROL           INNER JOIN OPERATIVOS ADMINCARTERA ON ADMINCARTERAROL.CodOperativo = ADMINCARTERA.CodOperativo         ON COMITENTES.CodComitente = ADMINCARTERAROL.CodComitente AND ADMINCARTERAROL.CodRol = 'AC'
        LEFT JOIN OPERATIVOSROLCMT ROLPRODUCTOR
        INNER JOIN OPERATIVOS PRODUCTOR ON PRODUCTOR.CodOperativo = ROLPRODUCTOR.CodOperativo
          ON ROLPRODUCTOR.CodComitente = COMITENTES.CodComitente AND ROLPRODUCTOR.CodRol = 'PR'   
        INNER JOIN (select MINUTAS.CodMinuta, MINUTAS.CodTpOperacionBurs, MINUTAS.Cantidad, MINUTAS.Precio, MINUTAS.HoraCarga, MINUTAS.HoraOferta, MINUTAS.FechaConcertacion, MINUTAS.Plazo, MINUTAS.FechaLiquidacion, MINUTAS.NumRegistro, MINUTAS.CantAsignada, MINUTAS.NumMinuta, MINUTAS.ConArancelExtra, MINUTAS.CodAgenteDepo, MINUTAS.CodEspecie, MINUTAS.CodSerie, MINUTAS.CodSerieHist, MINUTAS.CodIndice, MINUTAS.CodAgente, MINUTAS.CodOperador, MINUTAS.CodCtaContableDVP, CTASCONTABLES.Descripcion as DescripcionDVP,SERIESHIST.CantAccionesLt FROM   MINUTAS INNER JOIN (select INSTRUMENTOS.Codigo, CodEspecie ,INSTRUMENTOS.CodTpEspecie, INSTRUMENTOS.Abreviatura , CodSerie ,CodSerieHist ,CodIndice ,INSTRUMENTOS.Descripcion, Codigo as CodigoInstrumento,CodInstrumento, CodFondo FROM vwInstrumentos INSTRUMENTOS LEFT JOIN TPESPECIE ON TPESPECIE.CodTpEspecie = INSTRUMENTOS.CodTpEspecie) INSTRUMENTOS ON MINUTAS.CodEspecie = INSTRUMENTOS.CodEspecie AND COALESCE(MINUTAS.CodSerie, -1) = COALESCE(INSTRUMENTOS.CodSerie, -1) AND COALESCE(MINUTAS.CodSerieHist, -1) = COALESCE(INSTRUMENTOS.CodSerieHist, -1) AND COALESCE(MINUTAS.CodIndice, -1) = COALESCE(INSTRUMENTOS.CodIndice, -1) INNER JOIN TPOPERACIONBURS TPOPERMINU ON MINUTAS.CodTpOperacionBurs = TPOPERMINU.CodTpOperacionBurs INNER JOIN AGENTES ON MINUTAS.CodAgente = AGENTES.CodAgente LEFT JOIN SERIESHIST on SERIESHIST.CodEspecie = MINUTAS.CodEspecie AND SERIESHIST.CodSerie = MINUTAS.CodSerie AND SERIESHIST.EstaAnulado = 0 AND SERIESHIST.FechaDesde = (SELECT MAX(ESH2.FechaDesde) FROM SERIESHIST ESH2 WHERE MINUTAS.CodTpOperacionBurs IN ('OPCL', 'OPVL', 'OPCT', 'OPVT') AND ESH2.CodEspecie = MINUTAS.CodEspecie AND ESH2.CodSerie = MINUTAS.CodSerie AND ESH2.EstaAnulado = 0 AND ESH2.FechaDesde <= MINUTAS.FechaConcertacion) INNER JOIN AGENTESDEPO INNER JOIN DEPOSITARIOS ON AGENTESDEPO.CodDepositario = DEPOSITARIOS.CodDepositario ON MINUTAS.CodAgenteDepo = AGENTESDEPO.CodAgenteDepo LEFT JOIN OPERADORES ON MINUTAS.CodOperador = OPERADORES.CodOperador LEFT JOIN CTASCONTABLES WITH(INDEX=XPKCTASCONTABLES) ON MINUTAS.CodCtaContableDVP = CTASCONTABLES.CodCtaContable WHERE ((MINUTAS.FechaConcertacion >= CONVERT(VARCHAR,GETDATE(),112) AND MINUTAS.FechaConcertacion <= CONVERT(VARCHAR, GETDATE(),112))) AND ((MINUTAS.FechaLiquidacion >= CONVERT(VARCHAR, GETDATE(), 112) AND MINUTAS.FechaLiquidacion <= CONVERT(VARCHAR, GETDATE(),112)))) MINUTAS      
        INNER JOIN (select INSTRUMENTOS.Codigo, CodEspecie ,INSTRUMENTOS.CodTpEspecie, INSTRUMENTOS.Abreviatura , CodSerie ,CodSerieHist ,CodIndice ,INSTRUMENTOS.Descripcion, Codigo as CodigoInstrumento,CodInstrumento, CodFondo FROM vwInstrumentos INSTRUMENTOS LEFT JOIN TPESPECIE ON TPESPECIE.CodTpEspecie = INSTRUMENTOS.CodTpEspecie) INSTRUMENTOS ON MINUTAS.CodEspecie = INSTRUMENTOS.CodEspecie AND COALESCE(MINUTAS.CodSerie, -1) = COALESCE(INSTRUMENTOS.CodSerie, -1) AND COALESCE(MINUTAS.CodSerieHist, -1) = COALESCE(INSTRUMENTOS.CodSerieHist, -1) AND COALESCE(MINUTAS.CodIndice, -1) = COALESCE(INSTRUMENTOS.CodIndice, -1)      
        INNER JOIN TPOPERACIONBURS TPOPERMINU ON MINUTAS.CodTpOperacionBurs = TPOPERMINU.CodTpOperacionBurs      INNER JOIN AGENTES ON MINUTAS.CodAgente = AGENTES.CodAgente   ON #MINUTASASIG.CodMinuta = MINUTAS.CodMinuta   INNER JOIN AGENTESDEPO      INNER JOIN DEPOSITARIOS ON AGENTESDEPO.CodDepositario = DEPOSITARIOS.CodDepositario   ON MINUTAS.CodAgenteDepo = AGENTESDEPO.CodAgenteDepo   LEFT JOIN ORDENESBURS ON #MINUTASASIG.CodOrdenBurs = ORDENESBURS.CodOrdenBurs   INNER JOIN MONEDAS MONEDASOPER ON MONEDASOPER.CodMoneda = #MINUTASASIG.CodMonedaOper   
        INNER JOIN MONEDAS MONEDASAR ON MONEDASAR.CodMoneda = #MINUTASASIG.CodMonedaAr   
        INNER JOIN MONEDAS MONEDASDER ON MONEDASDER.CodMoneda = #MINUTASASIG.CodMonedaDer   
        INNER JOIN COMITENTES CMT2 ON CMT2.CodComitente = #MINUTASASIG.CodComitente   
        LEFT JOIN CATEGORIAS ON CATEGORIAS.CodCategoria = CMT2.CodCategoria   
        LEFT JOIN TPOPERACIONESCTA ON TPOPERACIONESCTA.CodTpOperacionCta = MINUTASASIG.CodTpOperacionCta
        group by COMITENTES.NumComitente, 
                 COMITENTES.Descripcion, 
                 MONEDASAR.CodMoneda
        order by COMITENTES.NumComitente";

        $stmt3 = $this->dbh->prepare($sql3);
        $stmt3->execute();
        $results3=$stmt3->fetchAll(PDO::FETCH_ASSOC);

//        $test = array();
//        $res = array();
//        $fruta = "";
//        foreach ($results1 as $k => $v ){
//            $res['asd'] = $v['NumComitente'];
//            $test[] = $v['NumComitente'];
//            $fruta .= $v['NumComitente'].",";
//        }
        
        $comitentes = array_merge($results1, $results2, $results3);

        $arrayComitentes = "";
        
        foreach ($comitentes as $comit){
            $arrayComitentes .= $comit['NumComitente'].",";
        }
        
        $arrayComitentes = substr($arrayComitentes, 0, -1);
        
        //Saldos mayores a 1.000.000
        

        if(!empty($arrayComitentes)){ //1042,1172
            $sql4 = "SELECT NumComitente,
                DescComitente as Comitente, 
                d.CodMoneda,
                0 as Tomadora,
                0 as Colocadora,
                0 as Inmediato,
                SaldoDisponible,
                SaldoNoDisponible,
                SaldoTotal
                FROM test.dbo.Disponible d
                INNER JOIN MONEDAS MONEDASAR ON MONEDASAR.CodMoneda = d.CodMoneda AND MONEDASAR.Simbolo = '$'  
                WHERE d.EstaAnulado = 0
                AND NumComitente IN ( {$arrayComitentes} )"
                ;
        }else{
            $sql4 = "";
        }
                
        $stmt4 = $this->dbh->prepare($sql4);
        $stmt4->execute();
        $results4=$stmt4->fetchAll(PDO::FETCH_ASSOC);
        
        unset($stmt);
        unset($stmt1);
        unset($stmt2);
        unset($stmt3);
        unset($stmt4);
        
        $results = array_merge($results1, $results2, $results3, $results4);

// Guardo en tabla Tesoreria   /////////////////////////////////////////////////     
     
        R::exec("truncate table tesoreria");
        
        $count = 0;

        foreach ($results as $k => $v){
            
            $count++;
            $this->load->model('Tesoreria_model');
            $this->Tesoreria_model->id = $count;
            $this->Tesoreria_model->codMoneda = $v['CodMoneda'];
            $this->Tesoreria_model->numComitente = $v['NumComitente'];
            $this->Tesoreria_model->comitente = $v['Comitente'];
            $this->Tesoreria_model->tomadora = $v['Tomadora'];
            $this->Tesoreria_model->colocadora = $v['Colocadora'];
            $this->Tesoreria_model->inmediato = $v['Inmediato'];
            $this->Tesoreria_model->saldoDisponible = $v['SaldoDisponible'];
            $this->Tesoreria_model->saldoNoDisponible = $v['SaldoNoDisponible'];
            $this->Tesoreria_model->saldoTotal = $v['SaldoTotal'];  
            
            $resultado = $this->Tesoreria_model->saveOrden();
            
//            $tesoreria->numComitente = $count;
//            $tesoreria->codMoneda = $count;
            
//            $tesoreria->numComitente = $result['NumComitente'];
//            $tesoreria->comitente = $result['Comitente'];
//            $tesoreria->tomadora = $result['Tomadora'];
//            $tesoreria->colocadora = $result['Colocadora'];
//            $tesoreria->inmediato = $result['Inmediato'];
//            $tesoreria->saldoDisponible = $result['SaldoDisponible'];
//            $tesoreria->saldoNoDisponible = $result['SaldoNoDisponible'];
//            $tesoreria->saldoTotal = $result['SaldoTotal'];   
        }
        
//        echo "<pre>";
//        print_r($resultado);
//        echo "<pre>";
        
////////////////////////////////////////////////////////////////////////////////

             
////////////////////////////////////////////////////////////////////////////////
        /*
        echo "<pre>";
        print_r($results);
        echo "<pre>";
        
        R::selectDatabase('default');
        
        R::exec("truncate table tesoreria");
        
        $count = 0;       
        
        foreach ($results as $k => $v){
            
            echo "<pre>";
            print_r($k);
            echo "<pre>";
            
            $count++;
            
            R::exec("INSERT INTO tesoreria (codMoneda, numComitente) values({$k},{$count})");
                       
            $tesoreria = R::dispense('tesoreria');         
//            
            $tesoreria->id = $count;
            $tesoreria->numComitente = $count;
            $tesoreria->codMoneda = $count;
            
//            $tesoreria->numComitente = $result['NumComitente'];
//            $tesoreria->comitente = $result['Comitente'];
//            $tesoreria->tomadora = $result['Tomadora'];
//            $tesoreria->colocadora = $result['Colocadora'];
//            $tesoreria->inmediato = $result['Inmediato'];
//            $tesoreria->saldoDisponible = $result['SaldoDisponible'];
//            $tesoreria->saldoNoDisponible = $result['SaldoNoDisponible'];
//            $tesoreria->saldoTotal = $result['SaldoTotal'];            
            R::store($tesoreria);
        }        
        
        echo "<pre>";
        print_r($count);
        echo "<pre>";
        
//        R::addDatabase('ordenes', 'mysql:host=ordenes.allaria.test;dbname=ordenes', 'root','25DeMayo');
//        R::addDatabase('operaciones', 'mysql:host=ordenes.allaria.xyz;dbname=operaciones', 'root','25DeMayo');
        */
////////////////////////////////////////////////////////////////////////////////
        
        $sql = "SELECT codMoneda, numComitente, comitente, SUM(tomadora) as tomadora, SUM(colocadora) as colocadora, SUM(inmediato) as inmediato, SUM(saldoDisponible) as saldoDisponible, SUM(saldoNoDisponible) as saldoNoDisponible, SUM(saldoTotal) as saldoTotal
                FROM   tesoreria
                WHERE codMoneda = 1
                GROUP BY codMoneda, numComitente, comitente
                ORDER BY numComitente ASC";
        $resultados = R::getAll($sql);
        
//        echo "<pre>";
//        print_r($resultados);
//        echo "<pre>";
//        die;
        
//        return $this->utf8_converter($results);
        
        if($resultados == false){
            return $resultados;
        }else{
            return $this->utf8_converter($resultados);
        }
    }
    
    
    public function grillaSaldosDisponibles(){

        $sql = "SELECT NumComitente,
                DescComitente as Comitente, 
                d.CodMoneda,
                0 as Tomadora,
                0 as Colocadora,
                0 as Inmediato,
                SaldoDisponible,
                SaldoNoDisponible,
                SaldoTotal
                FROM test.dbo.Disponible d
                INNER JOIN MONEDAS MONEDASAR ON MONEDASAR.CodMoneda = d.CodMoneda AND MONEDASAR.Simbolo = '$'  
                WHERE d.EstaAnulado = 0
                AND NumComitente IN (1042,1172)";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);

        unset($stmt);
//        return $this->utf8_converter($results);
        
        if($results == false){
            return $results;
        }else{
            return $this->utf8_converter($results);
        }        
    }
    
    
    
    public function getComitente(){

        $sql = "select  c.Descripcion comitente,
                        c.EsFisico esFisico,
                        o.Apellido + ', ' + o.Nombre as oficial,
                        ISNULL(jur.CUIT, isnull(p.CUIL, p.CUIT)) as cuit,
                        pri.Descripcion as CodPRI,
                        con.Posicion as posicion
                from    COMITENTES c
                join    OPERATIVOSROLCMT orc
                on      orc.CodComitente = c.CodComitente
                join    OPERATIVOS o
                on      o.CodOperativo   = orc.CodOperativo
                and     orc.CodRol       = 'OC'
                left outer join CONDOMINIOS con
                on      con.CodComitente = c.CodComitente
                join    PERSONAS p
                on      p.CodPersona     = con.CodPersona
                left outer join CMTJURIDICOS jur
                on      c.CodComitente   = jur.CodComitente
                left outer join PERFILRIESGOINVERSOR pri
                ON      pri.CodPRI = c.CodPRI
                where   c.NumComitente   = {$this->numComitente}
                and     con.CodTpCondominio = 'TI'
                and     con.EstaAnulado     = 0
                and     c.EstaAnulado       = 0
                order by con.Posicion";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);

        unset($stmt);
//        return $this->utf8_converter($results);
        
//        $test = $this->getPosicion();

        
        
        if($results == false){
            return $results;
        }else{
            return $this->utf8_converter($results);
        }        
    }

    
    public function getEspecie(){

        $sql = "select  
                        e.CodigoEspecie,
                        e.Descripcion,
                        e.Abreviatura
                from    ESPECIES e
                
                where   e.CodigoEspecie   = {$this->codEspecie}
                and     e.EstaAnulado       = 0
                ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);

        unset($stmt);
//        return $this->utf8_converter($results);
        
        if($results == false){
            return $results;
        }else{
            return $this->utf8_converter($results);
        }        
    }    
    
    
    
    public function getEspecieDescripcion(){

        $sql = "select  
                        e.CodigoEspecie,
                        e.Descripcion,
                        e.Abreviatura
                from    ESPECIES e
                
                where   e.Abreviatura   = '{$this->especie}'
                and     e.EstaAnulado       = 0
                ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);

        unset($stmt);
//        return $this->utf8_converter($results);
        
        if($results == false){
            return $results;
        }else{
            return $this->utf8_converter($results);
        }        
    }    
    
    
    public function getBoleto(){
                
//        R::addDatabase('operaciones', 'mysql:host=operaciones.allaria.test;dbname=operaciones', 'root','25DeMayo');
//        R::addDatabase('operaciones', 'mysql:host=ordenes.allaria.xyz;dbname=operaciones', 'root','25DeMayo');
//        R::selectDatabase('operaciones');       
//        
//        $sql = "SELECT *
//                FROM   boleto
//                WHERE  numBoleto = ({$this->numBoleto}) and fechaConcertacion >= DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 3 MONTH ";
//        $resultado = R::getRow($sql);
//        
//        R::selectDatabase('default');       
//        
//        return $resultado;
        
        $sql = "select BOLETOS.*, MO.Descripcion as Moneda, C.NumComitente, C.Descripcion as ComitenteDescripcion, TPO.Descripcion as TPODescripcion, TPO.Descripcion as TPODescripcion, E.CodTpInstrumento, E.Abreviatura as EspAbreviatura, TPI.Descripcion as TPIDescripcion, MAS.PorcArancel as PorcArancel
                FROM BOLETOS
                LEFT JOIN COMITENTES C
                ON BOLETOS.CodComitente = C.CodComitente
                LEFT JOIN MONEDAS MO
                ON BOLETOS.CodMoneda = MO.CodMoneda
                LEFT JOIN TPOPERACIONBURS TPO 
                ON BOLETOS.CodTpOperacionBurs = TPO.CodTpOperacionBurs
                LEFT JOIN ESPECIES E
                ON BOLETOS.CodEspecie = E.CodEspecie
                LEFT JOIN TPINSTRUMENTO TPI
                ON E.CodTpInstrumento = TPI.CodTpInstrumento
                LEFT JOIN MINUTASBOL MB
                ON BOLETOS.CodBoleto = MB.CodBoleto
                LEFT JOIN MINUTASASIG MAS
                ON MB.CodMinutaAsig = MAS.CodMinutaAsig
                WHERE BOLETOS.EstaAnulado = 0 AND NumBoleto = ({$this->numBoleto}) and BOLETOS.FechaConcertacion >= DATEADD(MONTH, -3, GETDATE())";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        unset($stmt);

        if($results == false){
            return $results;
        }else{
            return $this->utf8_converter($results);
        }
    }    
    
    public function getBoletoAnulado(){
        $sql = "select * FROM BOLETOS WHERE EstaAnulado = -1 AND NumBoleto = {$this->numBoleto} ORDER BY CodBoleto DESC";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        unset($stmt);

        if($results == false){
            return $results;
        }else{
            return $this->utf8_converter($results);
        }
    }

    // public function getMinuta(){
    //
    //     // $sql = "select  *
    //     //         from    MINUTAS
    //     //         where   NumRegistro   = {$this->numRegistro}";
    //     // $sql = "select  *
    //     //         from    dbo.MINUTAS m
    //     //         inner join dbo.MINUTASASIG ma
    //     //         on m.CodMinuta = ma.CodMinuta
    //     //         where   NumRegistro   = {$this->numRegistro}";
    //     // $sql = "SELECT m.CodMinuta, m.NumRegistro, m.CodTpOperacionBurs, m.CodEspecie, m.CodMoneda, m.EstaAnulado, m.FechaCarga, ma.CodComitente, c.NumComitente, ma.CodComitente, c.Descripcion
    //     //         FROM [dbo].[MINUTAS] m
    //     //
    //     //         LEFT JOIN MINUTASASIG ma
    //     //         ON m.CodMinuta = ma.CodMinuta
    //     //
    //     //         LEFT JOIN COMITENTES c
    //     //         ON ma.CodComitente = c.CodComitente
    //     //
    //     //         WHERE FechaCarga >= DATEADD(DAY, -4, GETDATE())
    //     //         AND m.NumRegistro = {$this->numRegistro}
    //     //         ORDER BY FechaCarga DESC";
    //     $sql = "SELECT m.CodMinuta, m.NumRegistro, m.CodTpOperacionBurs, tpo.Descripcion as TipoOperacionBursDesc, m.CodEspecie, tpe.Abreviatura as EspecieAbreviatura, m.CodMoneda, tpm.Descripcion as MonedaDescripcion, m.EstaAnulado, m.FechaCarga, ma.CodComitente, c.NumComitente, ma.CodComitente, c.Descripcion
    //             FROM [dbo].[MINUTAS] m
    //
    //             LEFT JOIN MINUTASASIG ma
    //             ON m.CodMinuta = ma.CodMinuta
    //
    //             LEFT JOIN COMITENTES c
    //             ON ma.CodComitente = c.CodComitente
    //
    //             LEFT JOIN TPOPERACIONBURS tpo
    //             ON m.CodTpOperacionBurs = tpo.CodTpOperacionBurs
    //
    //             LEFT JOIN MONEDAS tpm
    //             ON m.CodMoneda = tpm.CodMoneda
    //
    //             LEFT JOIN ESPECIES tpe
    //             ON m.CodEspecie = tpe.CodEspecie
    //
    //             WHERE FechaCarga >= DATEADD(DAY, -4, GETDATE())
    //             AND m.NumRegistro = {$this->numRegistro}
    //             ORDER BY FechaCarga DESC";
    //     $stmt = $this->dbh->prepare($sql);
    //     $stmt->execute();
    //     $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
    //
    //     //Ayer
    //     //WHERE FechaCarga >= DATEADD(DAY, -1, GETDATE())
    //
    //     //Hoy
    //     //WHERE FechaCarga > (SELECT CONVERT(date, GETDATE()))
    //
    //     unset($stmt);
    //
    //     if($results == false){
    //         return $results;
    //     }else{
    //         return $this->utf8_converter($results);
    //     }
    // }

    public function getMinuta(){
      if($this->numRegistro > 0 ){
            $sql = "SELECT m.CodMinuta, 
                    m.NumRegistro, 
                    c.NumComitente,
                    c.Descripcion, 
                    m.CodTpOperacionBurs, 
                    ob.Descripcion as TipoOperacionBursDesc, 
                    m.CodEspecie, 
                    e.Abreviatura, 
                    m.CodMoneda, 
                    mo.Descripcion as MonedaDescripcion,
                    m.Cantidad, 
                    m.FechaLiquidacion,
                    m.EstaAnulado, 
                    m.FechaCarga,
                    b.NumBoleto,
                    0 as esComitenteCorregido,
                    '' as numComitenteCorregido,
                    '' as comitenteCorregido,
                    0 as esArancelCorregido,
                    '' as arancelCorregido,
                    0 as esCantidadCorregido,
                    '' as cantidadCorregido,
                    '' as observaciones
                
                FROM dbo.MINUTAS m

                LEFT JOIN MINUTASASIG ma
                ON m.CodMinuta = ma.CodMinuta

                LEFT JOIN COMITENTES c
                ON ma.CodComitente = c.CodComitente

                LEFT JOIN ESPECIES e
                ON m.CodEspecie = e.CodEspecie

                LEFT JOIN TPOPERACIONBURS ob
                ON m.CodTpOperacionBurs = ob.CodTpOperacionBurs

                LEFT JOIN MONEDAS mo
                ON m.CodMoneda = mo.CodMoneda

                LEFT JOIN MINUTASBOL mb
                ON ma.CodMinutaAsig = mb.CodMinutaAsig

                LEFT JOIN BOLETOS b
                ON mb.CodBoleto = b.CodBoleto

                WHERE m.FechaCarga >= (SELECT CONVERT(date, GETDATE()))

                AND m.NumRegistro = {$this->numRegistro} ";

                if($this->numComitente > 0){
                    $sql .= " AND c.NumComitente = {$this->numComitente}";
                }
        }else{
            if($this->numComitente > 0){
                $sql = "SELECT m.CodMinuta, 
                    m.NumRegistro, 
                    c.NumComitente,
                    c.Descripcion, 
                    m.CodTpOperacionBurs, 
                    ob.Descripcion as TipoOperacionBursDesc, 
                    m.CodEspecie, 
                    e.Abreviatura, 
                    m.CodMoneda, 
                    mo.Descripcion as MonedaDescripcion,
                    m.Cantidad, 
                    m.FechaLiquidacion,
                    m.EstaAnulado, 
                    m.FechaCarga,
                    b.NumBoleto,
                    0 as esComitenteCorregido,
                    '' as numComitenteCorregido,
                    '' as comitenteCorregido,
                    0 as esArancelCorregido,
                    '' as arancelCorregido,
                    0 as esCantidadCorregido,
                    '' as cantidadCorregido,
                    '' as observaciones
                FROM dbo.MINUTAS m

                LEFT JOIN MINUTASASIG ma
                ON m.CodMinuta = ma.CodMinuta

                LEFT JOIN COMITENTES c
                ON ma.CodComitente = c.CodComitente

                LEFT JOIN ESPECIES e
                ON m.CodEspecie = e.CodEspecie

                LEFT JOIN TPOPERACIONBURS ob
                ON m.CodTpOperacionBurs = ob.CodTpOperacionBurs

                LEFT JOIN MONEDAS mo
                ON m.CodMoneda = mo.CodMoneda

                LEFT JOIN MINUTASBOL mb
                ON ma.CodMinutaAsig = mb.CodMinutaAsig

                LEFT JOIN BOLETOS b
                ON mb.CodBoleto = b.CodBoleto

                WHERE m.FechaCarga >= (SELECT CONVERT(date, GETDATE()))

                AND c.NumComitente = {$this->numComitente} ";
            }
        }
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);

        //Buscar el dato de ayer
        //WHERE FechaCarga >= DATEADD(DAY, -1, GETDATE())
        //WHERE m.FechaCarga >= (SELECT CONVERT(date, GETDATE()-1))
        //Buscar el dato de hoy
        //WHERE FechaCarga > (SELECT CONVERT(date, GETDATE()))
        unset($stmt);

        if($results == false){
            return $results;
        }else{
            return $this->utf8_converter($results);
        }

    }

    //20200706 se cambia el -3 a un 2 porqu oli dice que anda mal.
    function validarMinutaRegistroBoleto(){
        $sql = "SELECT m.CodMinuta, m.NumRegistro, m.FechaCarga, b.CodBoleto, b.EstaAnulado
                FROM MINUTAS m
                LEFT JOIN MINUTASASIG ma ON m.CodMinuta = ma.CodMinuta
                LEFT JOIN MINUTASBOL mb ON ma.CodMinutaAsig = mb.CodMinutaAsig
                LEFT JOIN BOLETOS b ON b.CodBoleto = mb.CodBoleto
                WHERE m.FechaCarga > (SELECT CONVERT(date, GETDATE()-2))
                AND b.CodBoleto > 0
				AND b.EstaAnulado != -1
                AND m.NumRegistro = {$this->numRegistro}";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);

        unset($stmt);
        // print_r($results);
        // die();
        if($results == false){
            return $results;
        }else{
            return $this->utf8_converter($results);
        }
    }

    //Se cabia el get date a -1 y luego a 0 o sea la fecha de hoy por pedido de Oli 28/08
    
    function validarMinutaComitenteBoleto(){
        $sql = "SELECT  m.CodMinuta, 
                        m.NumRegistro,
                        c.NumComitente, 
                        m.FechaCarga, 
                        b.CodBoleto
                FROM MINUTAS m

                LEFT JOIN MINUTASASIG ma 
                ON m.CodMinuta = ma.CodMinuta

		LEFT JOIN COMITENTES c
                ON ma.CodComitente = c.CodComitente
              
		LEFT JOIN MINUTASBOL mb 
                ON ma.CodMinutaAsig = mb.CodMinutaAsig
              
                LEFT JOIN BOLETOS b ON b.CodBoleto = mb.CodBoleto
              
		WHERE m.FechaCarga > (SELECT CONVERT(date, GETDATE()))
                AND b.CodBoleto > 0
                AND c.NumComitente = {$this->numComitente}";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);

        unset($stmt);
        // print_r($results);
        // die();
        if($results == false){
            return false;
        }else{
            return $this->utf8_converter($results);
        }
    }


    function utf8_converter($array){
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });

        return $array;
    }

    function getPosicionMonetaria(){
        /*
        $sql0 = "exec sp_disponible";
        $stmt0 = $this->dbhTest->prepare($sql0);
        $stmt0->execute();
        $results0=$stmt0->fetch(PDO::FETCH_ASSOC);
        unset($stmt0);
        $sql = "select distinct NumComitente, SimbMoneda, DescComitente, SaldoDisponible from GRILLA_70554751 where SimbMoneda in ('$') and SaldoDisponible > 0";
        $stmt = $this->dbhTest->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt);
        return $this->utf8_converter($results);
         *
         */
    }

    function getPosicionLebacs(){
        $sql = "                SELECT		COMITENTES.NumComitente,
                        CTASCORRIENTESPAP.CodComitenteRel,
                        -1,
                        '',
                        PAPELES.CodInstrumento,
                        SUM(CTASCORRIENTESPAP.Cantidad) AS Cantidad,
                        SERIESHIST.CantAccionesLt,
                        0
                FROM CTASCORRIENTES
                    INNER JOIN CTASCORRIENTESPAP
                            ON CTASCORRIENTESPAP.CodComitenteRel = CTASCORRIENTES.CodComitente
                            AND CTASCORRIENTESPAP.CodCtaCorriente = CTASCORRIENTES.CodCtaCorriente
                    INNER JOIN (
                            select  COMITENTES.CodComitente											CodComitente ,
                                            COMITENTES.Descripcion											Descripcion,
                                            COMITENTES.NumComitente											NumComitente,
                                            OFICIAL.CodOperativo											CodOficial,
                                            OFICIAL.Apellido + ', ' + OFICIAL.Nombre						Oficial,
                                            ADCARTERA.Apellido + ', ' + ADCARTERA.Nombre					Administrador,
                                            LEFT(COMITENTES.EmailsInfo,255)									EMail,
                                            ISNULL(dbo.fnDomicilioEnt(0, COMITENTES.CodComitente, 'DO', 3),
                                                    ISNULL(dbo.fnDomicilioEnt(0, COMITENTES.CodComitente, 'EC', 3),
                                                    dbo.fnDomicilioEnt(0, COMITENTES.CodComitente, 'PA', 3)))	Telefono,
                                            TPCOMITENTE.Descripcion											TpComitente,
                                            CANALESVTA.Descripcion											CanalVta,
                                            CATEGORIAS.Descripcion											DescCategoria,
                                            dbo.fnCmtBloqueado(COMITENTES.CodComitente)						CmtBloqueado
                            FROM  COMITENTES
                            LEFT JOIN SUCURSALES ON COMITENTES.CodSucursal = SUCURSALES.CodSucursal
                            LEFT JOIN OPERATIVOSROLCMT OFICIALROL INNER JOIN OPERATIVOS OFICIAL ON OFICIAL.CodOperativo = OFICIALROL.CodOperativo AND OFICIALROL.CodRol = 'OC'  ON OFICIALROL.CodComitente = COMITENTES.CodComitente  LEFT JOIN OPERATIVOSROLCMT ADCARTERAROL
                            INNER JOIN OPERATIVOS ADCARTERA ON ADCARTERA.CodOperativo = ADCARTERAROL.CodOperativo AND ADCARTERAROL.CodRol = 'AC'  ON ADCARTERAROL.CodComitente = COMITENTES.CodComitente  LEFT JOIN TPCOMITENTE ON COMITENTES.CodTpComitente = TPCOMITENTE.CodTpComitente  LEFT JOIN CANALESVTA ON COMITENTES.CodCanalVta = CANALESVTA.CodCanalVta  LEFT JOIN CARTERASADMINCMTHIST     INNER JOIN CARTERASADMIN ON CARTERASADMIN.CodCarteraAdmin = CARTERASADMINCMTHIST.CodCarteraAdmin
                            ON CARTERASADMINCMTHIST.CodComitente = COMITENTES.CodComitente AND ((GETDATE() >= CARTERASADMINCMTHIST.FechaIngreso AND FechaEgreso IS NULL ) OR GETDATE() BETWEEN CARTERASADMINCMTHIST.FechaIngreso AND DATEADD(day,-1, CARTERASADMINCMTHIST.FechaEgreso)) LEFT JOIN CATEGORIAS ON CATEGORIAS.CodCategoria = COMITENTES.CodCategoria
                    ) COMITENTES
                            ON COMITENTES.CodComitente = CTASCORRIENTESPAP.CodComitenteRel
                            INNER JOIN (
                                    select DISTINCT
                                                    INSTRUMENTOS.CodEspecie			CodEspecie,
                                                    INSTRUMENTOS.CodTpEspecie		CodTpEspecie,
                                                    INSTRUMENTOS.Abreviatura		Abreviatura,
                                                    INSTRUMENTOS.CodSerie			CodSerie,
                                                    INSTRUMENTOS.CodIndice			CodIndice,
                                                    INSTRUMENTOS.Codigo				CodigoEspecie,
                                                    INSTRUMENTOS.CodTpInstrumento	CodTpInstrumento,
                                                    TpInstrumento					TpInstrumento,
                                                    TPESPECIE.Descripcion			TpEspecie,
                                                    COALESCE ('I' + CONVERT(Varchar(10),CodIndice), 'S' + CONVERT(Varchar(10),CodSerie) , 'E' + CONVERT(Varchar(10),INSTRUMENTOS.CodEspecie))
                                                                                                                    CodInstrumento,
                                                    ESPECIES.CodMoneda				CodMoneda,
                                                    MONEDAS.Descripcion				Moneda,
                                                    ABREVIATURASESP.Descripcion		AbrevEspIsin,
                                                    SECTORESINV.Descripcion			DescSectorInv,
                                                    ABREVCUSIP.Descripcion			Cusip
                                    FROM	dbo.vwInstrumentos INSTRUMENTOS
                                    INNER JOIN ESPECIES
                                    INNER JOIN MONEDAS
                                    ON MONEDAS.CodMoneda = ESPECIES.CodMoneda LEFT JOIN ABREVIATURASESP
                                    ON ABREVIATURASESP.CodEspecie = ESPECIES.CodEspecie
                                    AND CodTpAbreviatura = 'IS'
                                    ON ESPECIES.CodEspecie = INSTRUMENTOS.CodEspecie
                                    LEFT JOIN ABREVIATURASESP ABREVCUSIP
                                    ON ABREVCUSIP.CodEspecie = ESPECIES.CodEspecie
                                    AND ABREVCUSIP.CodTpAbreviatura = 'CS'
                                    LEFT JOIN TPESPECIE
                                    ON TPESPECIE.CodTpEspecie = INSTRUMENTOS.CodTpEspecie
                                    LEFT JOIN SECTORESINV
                                    ON SECTORESINV.CodSectorInv = ESPECIES.CodSectorInv
                                    WHERE  ((DATEDIFF (day,  cast(GETDATE() as varchar(8)), INSTRUMENTOS.FVencimiento) >= 0 OR INSTRUMENTOS.FVencimiento IS NULL))
                                    AND ((UPPER(INSTRUMENTOS.Abreviatura) LIKE UPPER('{$this->instrumento}') ))
                            ) PAPELES
                                    LEFT JOIN SERIESHIST
                                            ON PAPELES.CodEspecie = SERIESHIST.CodEspecie
                                            AND PAPELES.CodSerie = SERIESHIST.CodSerie
                                            AND (DATEDIFF(day, SERIESHIST.FechaDesde,
                                                    (SELECT MAX(FechaDesde)
                                                    FROM SERIESHIST ESPE
                                                    WHERE ESPE.CodEspecie = PAPELES.CodEspecie
                                                            AND ESPE.CodSerie = PAPELES.CodSerie
                                                            AND ESPE.EstaAnulado = 0
                                                            AND DATEDIFF(day, ESPE.FechaDesde, GETDATE())>=0 ) ) = 0 )
                                    ON CTASCORRIENTESPAP.CodEspecie = PAPELES.CodEspecie
                                    AND COALESCE(CTASCORRIENTESPAP.CodSerie,-1) = COALESCE(PAPELES.CodSerie,-1)
                                    AND COALESCE(CTASCORRIENTESPAP.CodIndice,-1) = COALESCE(PAPELES.CodIndice,-1)
            WHERE
                    (CTASCORRIENTESPAP.FechaLiquidacion <= GETDATE())
            GROUP BY
                    COMITENTES.NumComitente,
                    CTASCORRIENTESPAP.CodComitenteRel,
                    PAPELES.CodInstrumento,
                    SERIESHIST.CantAccionesLt
            HAVING
                    SUM(CTASCORRIENTESPAP.Cantidad) <> 0 ";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt);
        return $this->utf8_converter($results);
    }

    public function getInstrumento(){
        $sql = "select DISTINCT
                    INSTRUMENTOS.CodEspecie,
                    INSTRUMENTOS.FVencimiento,
                    INSTRUMENTOS.Abreviatura		Abreviatura
                FROM	dbo.vwInstrumentos INSTRUMENTOS
                INNER JOIN ESPECIES
                INNER JOIN MONEDAS
                ON MONEDAS.CodMoneda = ESPECIES.CodMoneda LEFT JOIN ABREVIATURASESP
                ON ABREVIATURASESP.CodEspecie = ESPECIES.CodEspecie
                AND CodTpAbreviatura = 'IS'
                ON ESPECIES.CodEspecie = INSTRUMENTOS.CodEspecie
                LEFT JOIN ABREVIATURASESP ABREVCUSIP
                ON ABREVCUSIP.CodEspecie = ESPECIES.CodEspecie
                AND ABREVCUSIP.CodTpAbreviatura = 'CS'
                LEFT JOIN TPESPECIE
                ON TPESPECIE.CodTpEspecie = INSTRUMENTOS.CodTpEspecie
                LEFT JOIN SECTORESINV
                ON SECTORESINV.CodSectorInv = ESPECIES.CodSectorInv
                WHERE  INSTRUMENTOS.FVencimiento = cast(convert(char(8), {$this->fecha}, 112) as datetime)
                AND TPESPECIE.Descripcion in ('LEBAC', 'LEBAC BONIFICADA')";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);
        unset($stmt);
        return $this->utf8_converter($results);
    }

    public function existeInstrumento(){
        $sql = "select count(*)
                FROM	dbo.vwInstrumentos INSTRUMENTOS
                INNER JOIN ESPECIES
                INNER JOIN MONEDAS
                ON MONEDAS.CodMoneda = ESPECIES.CodMoneda LEFT JOIN ABREVIATURASESP
                ON ABREVIATURASESP.CodEspecie = ESPECIES.CodEspecie
                AND CodTpAbreviatura = 'IS'
                ON ESPECIES.CodEspecie = INSTRUMENTOS.CodEspecie
                LEFT JOIN ABREVIATURASESP ABREVCUSIP
                ON ABREVCUSIP.CodEspecie = ESPECIES.CodEspecie
                AND ABREVCUSIP.CodTpAbreviatura = 'CS'
                LEFT JOIN TPESPECIE
                ON TPESPECIE.CodTpEspecie = INSTRUMENTOS.CodTpEspecie
                LEFT JOIN SECTORESINV
                ON SECTORESINV.CodSectorInv = ESPECIES.CodSectorInv
                WHERE  INSTRUMENTOS.Abreviatura = upper('{$this->instrumento}')
                AND TPESPECIE.Descripcion in ('LEBAC', 'LEBAC BONIFICADA')";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $filas = $stmt->fetchColumn();
        unset($stmt);
        if ($filas == 0){
            $result = array('existe'=>false);
        } else {
            $result = array('existe'=>true);
        }
        return $this->utf8_converter($result);
    }


    public function getFondo(){
        /* fondo */
//        $fondo = $this->input->post('fondo');
        $sql = "SELECT FONDOS.CodFondo, FONDOS.CodMoneda, FONDOS.Abreviatura, FONDOS.Descripcion, MONEDAS.Simbolo, FONDOS.EsFisico, FONDOS.EsFisicoJuridico "
                . "FROM FONDOS LEFT JOIN MONEDAS ON MONEDAS.CodMoneda = FONDOS.CodMoneda "
                . "WHERE FONDOS.EstaAnulado = 0 AND FONDOS.CodFondo = {$this->fondo}";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);
        unset($stmt);
        return $this->utf8_converter($results);
    }

    public function getFondos(){
        /* posicion fondos */
        $sql = "SELECT CodFondo, CodMoneda, Abreviatura, Descripcion FROM FONDOS WHERE EstaAnulado = 0";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt);
        return $this->utf8_converter($results);
    }


    public function getDisponible(){

        $filtro = '';

//        if($this->simbMoneda == ''){
//            $filtro = "AND SimbMoneda = '$'";
//        } else {
//            $filtro = "AND SimbMoneda = {$this->simbMoneda}";
//        }


        /* fondo */
        $sql = "select FMT_GRILLA, Oficial, Moneda, SimbMoneda, NumComitente, DescComitente, SaldoDisponible, CodAdministrador, Administrador,"
                . "CanalVenta, CodComitente, EstaAnulado, CodMoneda, CodOficial, SaldoNoDisponible, SaldoTotal, IDPosicion "
                . "from test.dbo.Disponible where NumComitente = {$this->numComitente}"
                . " {$filtro} ";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);

        unset($stmt);
        return $this->utf8_converter($results);
    }


    public function getValorCuota(){
        /* fondo */
//        $fondo = $this->input->post('fondo');
        $sql = "select f.CodFondo, f.Descripcion, c.Fecha, m.Simbolo, c.Cotizacion
        from FONDOS f join COTIZACIONESFDO c ON f.CodFondo = c.CodFondo join MONEDAS m on f.CodMoneda = m.CodMoneda
        WHERE f.CodFondo = {$this->fondo}
        and   f.EstaAnulado = 0
        and   c.EstaAnulado = 0
        and   c.Fecha = (SELECT max(h.Fecha) FROM COTIZACIONESFDO h where h.CodFondo = c.CodFondo and c.EstaAnulado = 0)
        and   f.EsFisicoJuridico is NOT NULL
        order by f.Descripcion";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);
        unset($stmt);
        return $this->utf8_converter($results);
    }













    public function getPosicionFondoNumeroComitente(){
        /* posicion fondos */
        $comitente = $this->input->post('numComitente');
        $fondo = $this->input->post('fondo');

        $sql = "
            select
                    COMITENTES.NumComitente,
                    COMITENTES.Descripcion,
                    COALESCE( OPERATIVOS.Apellido + ', ' + OPERATIVOS.Nombre, 'Sin Oficial' ) Oficial,
                    FONDOS.Descripcion FondoDescripcion,
                    FONDOS.Abreviatura FondoAbreviatura,
                    SUM( CTASCORRIENTESFDO.Cantidad ) Cantidad,
                    SUM( CTASCORRIENTESFDOND.Cantidad ) CantidadNoDisponible,
                    COMITENTES.CodComitente,
                    ISNULL( CANALESVTA.Descripcion,	'' ) CanalVenta,
                    COMITENTES.Contacto,
                    COMITENTES.EsCarteraPropia,
                    COMITENTES.EsFisico,
                    dbo.fnCmtBloqueado( COMITENTES.CodComitente ) Bloqueado,
                    COALESCE( PRODUCTOR.Apellido + ', ' + PRODUCTOR.Nombre,	'Sin Productor' ) Productor,
                    TPINVERSOR.Descripcion TipoInverso,
                    MAX(CTASCORRIENTESFDO.CodAgenteDepo) as CodAgenteDepo,
                    MAX(AGENTESDEPO.NumCuenta) as NumCuenta,
                    MAX(DEPOSITARIOS.Descripcion) as DepositariosDescripcion,
                    FONDOS.CodFondo,
                    MONEDAS.Simbolo SimboloMoneda,
                    SUM( CTASCORRIENTESFDO.Cantidad ) * ISNULL(RPT_POSICIONCUOTACMT.Cotizacion,	0 ) * ISNULL(RPT_POSICIONCUOTACMT.TpCambioCotPais,	1 ) MontoValuadoMonAplic,
                    SUM( CTASCORRIENTESFDO.Cantidad ) * ISNULL(RPT_POSICIONCUOTACMT.Cotizacion,	0 ), COALESCE( OPERATIVOS.CodOperativo,	-1 )            MontoValuadoMonFondo,
                    COALESCE( PRODUCTOR.CodOperativo,	-1 ) CodOperativo,
                    CASE
                            WHEN ISNULL(RPT_POSICIONCUOTACMT.MoneRescSusc,
                            0 ) = 0 THEN NULL
                            ELSE MFDO.Simbolo
                    END SimbMonedaFondo,
                    SUM( CASE WHEN RPT_POSICIONCUOTACMT.FltSaldoConsolidado = 0 THEN ( CASE WHEN CTASCORRIENTESFDO.EsACDI = -1 THEN ( CTASCORRIENTESFDO.Cantidad ) END ) END ) SaldoACDI,
                    SUM( CASE WHEN RPT_POSICIONCUOTACMT.FltSaldoConsolidado = 0 THEN ( CASE WHEN CTASCORRIENTESFDO.EsACDI = 0 THEN ( CTASCORRIENTESFDO.Cantidad ) END ) END ) SaldoColocadorSimple,
                    SUCURSALES.CodSucursal,
                    SUCURSALES.Descripcion,
                    SUCURSALES.NumSucursal,
                    RPT_POSICIONCUOTACMT.TpCambioCotPais CotizacionTipoCambio,
                    ISNULL(RPT_POSICIONCUOTACMT.Cotizacion,	0 ) ValorCuota
            FROM
                    CTASCORRIENTES
            INNER JOIN COMITENTES
            LEFT JOIN TPINVERSOR ON
                    TPINVERSOR.CodTpInversor = COMITENTES.CodTpInversor ON
                    COMITENTES.CodComitente = CTASCORRIENTES.CodComitente
                    AND CTASCORRIENTES.EstaAnulado = 0
            LEFT JOIN SUCURSALES ON
                    COMITENTES.CodSucursal = SUCURSALES.CodSucursal
            LEFT JOIN OPERATIVOSROLCMT
            INNER JOIN OPERATIVOS ON
                    OPERATIVOS.CodOperativo = OPERATIVOSROLCMT.CodOperativo ON
                    OPERATIVOSROLCMT.CodComitente = CTASCORRIENTES.CodComitente
                    AND OPERATIVOSROLCMT.CodRol = 'OC'
            LEFT JOIN OPERATIVOSROLCMT ROLPRODUCTOR
            INNER JOIN OPERATIVOS PRODUCTOR ON
                    PRODUCTOR.CodOperativo = ROLPRODUCTOR.CodOperativo ON
                    ROLPRODUCTOR.CodComitente = CTASCORRIENTES.CodComitente
                    AND ROLPRODUCTOR.CodRol = 'PR'
            INNER JOIN CTASCORRIENTESFDO
            INNER JOIN FONDOS ON
                    FONDOS.CodFondo = CTASCORRIENTESFDO.CodFondo
            LEFT JOIN AGENTESDEPO
            INNER JOIN DEPOSITARIOS ON
                    AGENTESDEPO.CodDepositario = DEPOSITARIOS.CodDepositario ON
                    CTASCORRIENTESFDO.CodAgenteDepo = AGENTESDEPO.CodAgenteDepo ON
                    CTASCORRIENTESFDO.CodCtaCorriente = CTASCORRIENTES.CodCtaCorriente
            LEFT JOIN CANALESVTA ON
                    CANALESVTA.CodCanalVta = COMITENTES.CodCanalVta
            LEFT JOIN CTASCORRIENTESFDOND ON
                    CTASCORRIENTESFDOND.CodCtaCorriente = CTASCORRIENTES.CodCtaCorriente
                    AND FONDOS.CodFondo = CTASCORRIENTESFDOND.CodFondo
            LEFT JOIN (
                    Select
                    FONDOS.CodFondo CodFondo,
                    COTIZACIONESFDO.Fecha Fecha,
                    COTIZACIONESFDO.Cotizacion Cotizacion,
                    dbo.fnUltCotizacionMon( getdate(),	FONDOS.CodMoneda,	dbo.fnTraerMonedaPaisApl() ) TpCambioCotPais,
                    -1 MoneRescSusc,
                    0 FltSaldoConsolidado
            from
                    FONDOS
            inner join COTIZACIONESFDO on
                    FONDOS.CodFondo = COTIZACIONESFDO.CodFondo
            WHERE
                    COTIZACIONESFDO.EstaAnulado = 0
                    AND FONDOS.EstaAnulado = 0
                    AND COTIZACIONESFDO.Fecha = (
                    SELECT
                            max( COTI.Fecha )
                    FROM
                            COTIZACIONESFDO COTI
                    WHERE
                            COTI.CodFondo = COTIZACIONESFDO.CodFondo
                            AND COTI.Fecha <= getdate()
                            AND COTI.EstaAnulado = 0 )
            ) RPT_POSICIONCUOTACMT ON
                    RPT_POSICIONCUOTACMT.CodFondo = FONDOS.CodFondo
            INNER JOIN MONEDAS ON
                    MONEDAS.CodMoneda = FONDOS.CodMoneda
            LEFT JOIN MONEDAS MFDO ON
                    MFDO.CodMoneda = CTASCORRIENTESFDO.CodMoneda
            WHERE
                    (( CTASCORRIENTES.FechaConcertacion <= getDate() ))
            AND     COMITENTES.NumComitente = {$this->numComitente}
            AND     FONDOS.CodFondo = {$this->fondo}
            GROUP BY
                    COMITENTES.NumComitente,
                    COMITENTES.Descripcion,
                    COALESCE( OPERATIVOS.Apellido + ', ' + OPERATIVOS.Nombre,
                    'Sin Oficial' ),
                    FONDOS.Descripcion,
                    FONDOS.Abreviatura,
                    COMITENTES.CodComitente,
                    ISNULL( CANALESVTA.Descripcion,
                    '' ),
                    COMITENTES.Contacto,
                    COMITENTES.EsCarteraPropia,
                    COMITENTES.EsFisico,
                    dbo.fnCmtBloqueado( COMITENTES.CodComitente ),
                    COALESCE( PRODUCTOR.Apellido + ', ' + PRODUCTOR.Nombre,
                    'Sin Productor' ),
                    TPINVERSOR.Descripcion,
                    FONDOS.CodFondo,
                    MONEDAS.Simbolo,
                    COALESCE( OPERATIVOS.CodOperativo,
                    -1 ),
                    COALESCE( PRODUCTOR.CodOperativo,
                    -1 ),
                    CASE
                            WHEN ISNULL(RPT_POSICIONCUOTACMT.MoneRescSusc,
                            0 ) = 0 THEN NULL
                            ELSE MFDO.Simbolo
                    END,
                    SUCURSALES.CodSucursal,
                    SUCURSALES.Descripcion,
                    SUCURSALES.NumSucursal,
                    RPT_POSICIONCUOTACMT.TpCambioCotPais,
                    ISNULL(RPT_POSICIONCUOTACMT.Cotizacion,
                    0 )
            HAVING
                    ( SUM( CTASCORRIENTESFDO.Cantidad ) <> 0 )

                ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);
        unset($stmt);
        return $this->utf8_converter($results);
    }


    public function getPosicionFondos(){
        /* posicion fondos */

        $sql = "
            select
                    COMITENTES.NumComitente,
                    COMITENTES.Descripcion,
                    COALESCE( OPERATIVOS.Apellido + ', ' + OPERATIVOS.Nombre, 'Sin Oficial' ) Oficial,
                    FONDOS.Descripcion FondoDescripcion,
                    FONDOS.Abreviatura FondoAbreviatura,
                    SUM( CTASCORRIENTESFDO.Cantidad ) Cantidad,
                    SUM( CTASCORRIENTESFDOND.Cantidad ) CantidadNoDisponible,
                    COMITENTES.CodComitente,
                    ISNULL( CANALESVTA.Descripcion,	'' ) CanalVenta,
                    COMITENTES.Contacto,
                    COMITENTES.EsCarteraPropia,
                    COMITENTES.EsFisico,
                    dbo.fnCmtBloqueado( COMITENTES.CodComitente ) Bloqueado,
                    COALESCE( PRODUCTOR.Apellido + ', ' + PRODUCTOR.Nombre,	'Sin Productor' ) Productor,
                    TPINVERSOR.Descripcion TipoInverso,
                    MAX(CTASCORRIENTESFDO.CodAgenteDepo) as CodAgenteDepo,
                    MAX(AGENTESDEPO.NumCuenta) as NumCuenta,
                    MAX(DEPOSITARIOS.Descripcion) as DepositariosDescripcion,
                    FONDOS.CodFondo,
                    MONEDAS.Simbolo SimboloMoneda,
                    SUM( CTASCORRIENTESFDO.Cantidad ) * ISNULL(RPT_POSICIONCUOTACMT.Cotizacion,	0 ) * ISNULL(RPT_POSICIONCUOTACMT.TpCambioCotPais,	1 ) MontoValuadoMonAplic,
                    SUM( CTASCORRIENTESFDO.Cantidad ) * ISNULL(RPT_POSICIONCUOTACMT.Cotizacion,	0 ), COALESCE( OPERATIVOS.CodOperativo,	-1 )            MontoValuadoMonFondo,
                    COALESCE( PRODUCTOR.CodOperativo,	-1 ) CodOperativo,
                    CASE
                            WHEN ISNULL(RPT_POSICIONCUOTACMT.MoneRescSusc,
                            0 ) = 0 THEN NULL
                            ELSE MFDO.Simbolo
                    END SimbMonedaFondo,
                    SUM( CASE WHEN RPT_POSICIONCUOTACMT.FltSaldoConsolidado = 0 THEN ( CASE WHEN CTASCORRIENTESFDO.EsACDI = -1 THEN ( CTASCORRIENTESFDO.Cantidad ) END ) END ) SaldoACDI,
                    SUM( CASE WHEN RPT_POSICIONCUOTACMT.FltSaldoConsolidado = 0 THEN ( CASE WHEN CTASCORRIENTESFDO.EsACDI = 0 THEN ( CTASCORRIENTESFDO.Cantidad ) END ) END ) SaldoColocadorSimple,
                    SUCURSALES.CodSucursal,
                    SUCURSALES.Descripcion,
                    SUCURSALES.NumSucursal,
                    RPT_POSICIONCUOTACMT.TpCambioCotPais CotizacionTipoCambio,
                    ISNULL(RPT_POSICIONCUOTACMT.Cotizacion,	0 ) ValorCuota
            FROM
                    CTASCORRIENTES
            INNER JOIN COMITENTES
            LEFT JOIN TPINVERSOR ON
                    TPINVERSOR.CodTpInversor = COMITENTES.CodTpInversor ON
                    COMITENTES.CodComitente = CTASCORRIENTES.CodComitente
                    AND CTASCORRIENTES.EstaAnulado = 0
            LEFT JOIN SUCURSALES ON
                    COMITENTES.CodSucursal = SUCURSALES.CodSucursal
            LEFT JOIN OPERATIVOSROLCMT
            INNER JOIN OPERATIVOS ON
                    OPERATIVOS.CodOperativo = OPERATIVOSROLCMT.CodOperativo ON
                    OPERATIVOSROLCMT.CodComitente = CTASCORRIENTES.CodComitente
                    AND OPERATIVOSROLCMT.CodRol = 'OC'
            LEFT JOIN OPERATIVOSROLCMT ROLPRODUCTOR
            INNER JOIN OPERATIVOS PRODUCTOR ON
                    PRODUCTOR.CodOperativo = ROLPRODUCTOR.CodOperativo ON
                    ROLPRODUCTOR.CodComitente = CTASCORRIENTES.CodComitente
                    AND ROLPRODUCTOR.CodRol = 'PR'
            INNER JOIN CTASCORRIENTESFDO
            INNER JOIN FONDOS ON
                    FONDOS.CodFondo = CTASCORRIENTESFDO.CodFondo
            LEFT JOIN AGENTESDEPO
            INNER JOIN DEPOSITARIOS ON
                    AGENTESDEPO.CodDepositario = DEPOSITARIOS.CodDepositario ON
                    CTASCORRIENTESFDO.CodAgenteDepo = AGENTESDEPO.CodAgenteDepo ON
                    CTASCORRIENTESFDO.CodCtaCorriente = CTASCORRIENTES.CodCtaCorriente
            LEFT JOIN CANALESVTA ON
                    CANALESVTA.CodCanalVta = COMITENTES.CodCanalVta
            LEFT JOIN CTASCORRIENTESFDOND ON
                    CTASCORRIENTESFDOND.CodCtaCorriente = CTASCORRIENTES.CodCtaCorriente
                    AND FONDOS.CodFondo = CTASCORRIENTESFDOND.CodFondo
            LEFT JOIN (
                    Select
                    FONDOS.CodFondo CodFondo,
                    COTIZACIONESFDO.Fecha Fecha,
                    COTIZACIONESFDO.Cotizacion Cotizacion,
                    dbo.fnUltCotizacionMon( getdate(),	FONDOS.CodMoneda,	dbo.fnTraerMonedaPaisApl() ) TpCambioCotPais,
                    -1 MoneRescSusc,
                    0 FltSaldoConsolidado
            from
                    FONDOS
            inner join COTIZACIONESFDO on
                    FONDOS.CodFondo = COTIZACIONESFDO.CodFondo
            WHERE
                    COTIZACIONESFDO.EstaAnulado = 0
                    AND FONDOS.EstaAnulado = 0
                    AND COTIZACIONESFDO.Fecha = (
                    SELECT
                            max( COTI.Fecha )
                    FROM
                            COTIZACIONESFDO COTI
                    WHERE
                            COTI.CodFondo = COTIZACIONESFDO.CodFondo
                            AND COTI.Fecha <= getdate()
                            AND COTI.EstaAnulado = 0 )
            ) RPT_POSICIONCUOTACMT ON
                    RPT_POSICIONCUOTACMT.CodFondo = FONDOS.CodFondo
            INNER JOIN MONEDAS ON
                    MONEDAS.CodMoneda = FONDOS.CodMoneda
            LEFT JOIN MONEDAS MFDO ON
                    MFDO.CodMoneda = CTASCORRIENTESFDO.CodMoneda
            WHERE
                    (( CTASCORRIENTES.FechaConcertacion <= getDate() ))
            AND     COMITENTES.NumComitente = {$this->numComitente}
            GROUP BY
                    COMITENTES.NumComitente,
                    COMITENTES.Descripcion,
                    COALESCE( OPERATIVOS.Apellido + ', ' + OPERATIVOS.Nombre,
                    'Sin Oficial' ),
                    FONDOS.Descripcion,
                    FONDOS.Abreviatura,
                    COMITENTES.CodComitente,
                    ISNULL( CANALESVTA.Descripcion,
                    '' ),
                    COMITENTES.Contacto,
                    COMITENTES.EsCarteraPropia,
                    COMITENTES.EsFisico,
                    dbo.fnCmtBloqueado( COMITENTES.CodComitente ),
                    COALESCE( PRODUCTOR.Apellido + ', ' + PRODUCTOR.Nombre,
                    'Sin Productor' ),
                    TPINVERSOR.Descripcion,
                    FONDOS.CodFondo,
                    MONEDAS.Simbolo,
                    COALESCE( OPERATIVOS.CodOperativo,
                    -1 ),
                    COALESCE( PRODUCTOR.CodOperativo,
                    -1 ),
                    CASE
                            WHEN ISNULL(RPT_POSICIONCUOTACMT.MoneRescSusc,
                            0 ) = 0 THEN NULL
                            ELSE MFDO.Simbolo
                    END,
                    SUCURSALES.CodSucursal,
                    SUCURSALES.Descripcion,
                    SUCURSALES.NumSucursal,
                    RPT_POSICIONCUOTACMT.TpCambioCotPais,
                    ISNULL(RPT_POSICIONCUOTACMT.Cotizacion,
                    0 )
            HAVING
                    ( SUM( CTASCORRIENTESFDO.Cantidad ) <> 0 )

                ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt);
        return $this->utf8_converter($results);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function getPosicion(){
        
        
        $fechaDesde = '';
//        $comitente = 1047;
        $comitente = $this->numComitente;
        $especie = $this->especie;
        

       
        if (isset($this->fechaDesde)) {
            $fechaDesde = $this->fechaDesde;
        } else {
            $fechaDesde = (string) date('Ymd');
        }
                
        if (isset($this->fechaHasta)) {
            $fechaHasta = $this->fechaHasta;
        } else {
            $fechaHasta = (string) date('Ymd');
        }
        
        $tableId = (string) getmypid();
                
        $sql = "
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#POSICIONTITCMT')) DROP TABLE #POSICIONTITCMT
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#INSTRUMENTOS')) DROP TABLE #INSTRUMENTOS
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_POSTIT_COMITENTES')) DROP TABLE #RPT_POSTIT_COMITENTES
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_POSTIT_COMITENTESND')) DROP TABLE #RPT_POSTIT_COMITENTESND
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_AGENTESDEPO')) DROP TABLE #RPT_AGENTESDEPO
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#POSTITMERCADOS')) DROP TABLE #POSTITMERCADOS

        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#POSICIONTITCMT')) DROP TABLE #POSICIONTITCMT
        CREATE TABLE #POSICIONTITCMT(CodComitente numeric(10,0) NOT NULL , NumComitente numeric(15,0) NOT NULL , Comitente varchar(80) COLLATE database_default  NOT NULL , TpComitente varchar(80) COLLATE database_default  NULL , CanalVta varchar(80) COLLATE database_default  NULL , EstaAnulado smallint NULL , CodEspecie numeric(10,0) NULL , CodInstrumento varchar(25) COLLATE database_default  NULL , CodTpInstrumento varchar(2) COLLATE database_default  NULL , TpInstrumento varchar(80) COLLATE database_default  NULL , CodigoInstrumento numeric(15,0) NULL , Instrumento varchar(150) COLLATE database_default  NULL , AbrevInstrumento varchar(30) COLLATE database_default  NULL , CodAgenteDepo numeric(3,0) NULL , Depositario varchar(80) COLLATE database_default  NULL , DepositarioCuenta varchar(30) COLLATE database_default  NULL , Cantidad numeric(22,10) NULL , CantidadNoDisponible numeric(22,10) NULL , CantidadTotal numeric(22,10) NULL , CodTpEspecie numeric(5,0) NULL , TpEspecie varchar(150) COLLATE database_default  NULL , AbrevEspIsin varchar(30) COLLATE database_default  NULL , CodMoneda numeric(5,0) NULL , Moneda varchar(80) COLLATE database_default  NULL , Importe numeric(19,2) NULL , ImporteMonPais numeric(19,2) NULL , ImporteMonPaisND numeric(19,2) NULL , ImporteMonPaisTotal numeric(19,2) NULL , Precio numeric(19,10) NULL , PrecioMonPais numeric(28,10) NULL , FechaUltCoti smalldatetime NULL , Teorica varchar(30) COLLATE database_default  NULL , CantAccionesLt numeric(22,10) NULL , CodOficial numeric(10,0) NULL , Oficial varchar(300) COLLATE database_default  NULL , Administrador varchar(300) COLLATE database_default  NULL , PorcValorResidual numeric(18,12) NULL , PrecioCieDol numeric(19,10) NULL , VMercadoDol numeric(19,2) NULL , FechaHasta smalldatetime NULL , PPP numeric(19,10) NULL , FechaPPP smalldatetime NULL , EMail varchar(255) COLLATE database_default  NULL , Telefono varchar(50) COLLATE database_default  NULL , PrecioMonDolar numeric(19,10) NULL , ImporteMonDolar numeric(19,2) NULL , FechaVencimiento smalldatetime NULL , InternalCode varchar(30) COLLATE database_default  NULL , DescCategoria varchar(80) COLLATE database_default  NULL , DescSectorInv varchar(80) COLLATE database_default  NULL , CmtBloqueado smallint NULL , CantidadVR numeric(22,10) NULL , Cusip varchar(30) COLLATE database_default  NULL , CodMercado numeric(5,0) NULL , Mercado varchar(80) COLLATE database_default  NULL , NumCmtMercado numeric(12,0) NULL , CUIT varchar(14) COLLATE database_default  NULL )
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#INSTRUMENTOS')) DROP TABLE #INSTRUMENTOS
        CREATE TABLE #INSTRUMENTOS(CodEspecie numeric(10,0) NOT NULL , CodSerie numeric(10,0) NULL , CodInstrumento varchar(25) COLLATE database_default  NULL , CodTpInstrumento varchar(2) COLLATE database_default  NULL , TpInstrumento varchar(80) COLLATE database_default  NULL , CodTpEspecie numeric(5,0) NULL , TpEspecie varchar(150) COLLATE database_default  NULL , Abreviatura varchar(30) COLLATE database_default  NULL , CodIndice numeric(10,0) NULL , Descripcion varchar(80) COLLATE database_default  NULL , CodigoEspecie numeric(15,0) NULL , AbrevEspIsin varchar(30) COLLATE database_default  NULL , CodMoneda numeric(5,0) NOT NULL , Moneda varchar(80) COLLATE database_default  NOT NULL , CodMonedaCot numeric(5,0) NULL , Precio numeric(19,10) NULL , PrecioMonPais numeric(28,10) NULL , FechaUltCoti smalldatetime NULL , Teorica varchar(30) COLLATE database_default  NULL , CodCotizacionEsp numeric(10,0) NULL , CodCotizacionSer numeric(10,0) NULL , CodCotizacionInd numeric(10,0) NULL , DescSectorInv varchar(80) COLLATE database_default  NULL , Cusip varchar(30) COLLATE database_default  NULL )
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_POSTIT_COMITENTES')) DROP TABLE #RPT_POSTIT_COMITENTES
        CREATE TABLE #RPT_POSTIT_COMITENTES(CodComitente numeric(10,0) NOT NULL , Descripcion varchar(80) COLLATE database_default  NULL , NumComitente numeric(15,0) NULL , EstaAnulado smallint NULL , CodOficial numeric(10,0) NULL , Oficial varchar(300) COLLATE database_default  NULL , Administrador varchar(300) COLLATE database_default  NULL , EMail varchar(255) COLLATE database_default  NULL , Telefono varchar(50) COLLATE database_default  NULL , TpComitente varchar(80) COLLATE database_default  NULL , CanalVta varchar(80) COLLATE database_default  NULL , DescCategoria varchar(80) COLLATE database_default  NULL , CmtBloqueado smallint NULL  PRIMARY KEY (CodComitente))
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_POSTIT_COMITENTESND')) DROP TABLE #RPT_POSTIT_COMITENTESND
        CREATE TABLE #RPT_POSTIT_COMITENTESND(CodInstrumento varchar(25) COLLATE database_default  NOT NULL , CodComitente numeric(10,0) NOT NULL , CantidadNoDisponible numeric(22,10) NOT NULL , CodAgenteDepo numeric(3,0) NULL )
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#RPT_AGENTESDEPO')) DROP TABLE #RPT_AGENTESDEPO
        CREATE TABLE #RPT_AGENTESDEPO(CodAgenteDepo numeric(3,0) NOT NULL , NumCuenta varchar(30) COLLATE database_default  NOT NULL , CodDepositario numeric(5,0) NOT NULL , Descripcion varchar(80) COLLATE database_default  NOT NULL )
        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#POSTITMERCADOS')) DROP TABLE #POSTITMERCADOS
        CREATE TABLE #POSTITMERCADOS(CodMercado numeric(5,0) NULL , Descripcion varchar(80) COLLATE database_default  NULL )
        INSERT #INSTRUMENTOS (CodEspecie , CodTpEspecie ,Abreviatura, CodSerie , CodIndice , CodigoEspecie ,CodTpInstrumento, TpInstrumento , TpEspecie, CodInstrumento , CodMoneda , Moneda, AbrevEspIsin, DescSectorInv, Cusip)   select DISTINCT INSTRUMENTOS.CodEspecie, INSTRUMENTOS.CodTpEspecie, INSTRUMENTOS.Abreviatura, INSTRUMENTOS.CodSerie , INSTRUMENTOS.CodIndice, INSTRUMENTOS.Codigo , INSTRUMENTOS.CodTpInstrumento, TpInstrumento , TPESPECIE.Descripcion, COALESCE ('I' + CONVERT(Varchar(10),CodIndice), 'S' + CONVERT(Varchar(10),CodSerie) , 'E' + CONVERT(Varchar(10),INSTRUMENTOS.CodEspecie)) , ESPECIES.CodMoneda , MONEDAS.Descripcion, ABREVIATURASESP.Descripcion , SECTORESINV.Descripcion, ABREVCUSIP.Descripcion FROM dbo.vwInstrumentos INSTRUMENTOS INNER JOIN ESPECIES INNER JOIN MONEDAS ON MONEDAS.CodMoneda = ESPECIES.CodMoneda LEFT JOIN ABREVIATURASESP ON ABREVIATURASESP.CodEspecie = ESPECIES.CodEspecie AND CodTpAbreviatura = 'IS' ON ESPECIES.CodEspecie = INSTRUMENTOS.CodEspecie LEFT JOIN ABREVIATURASESP ABREVCUSIP ON ABREVCUSIP.CodEspecie = ESPECIES.CodEspecie AND ABREVCUSIP.CodTpAbreviatura = 'CS' LEFT JOIN TPESPECIE ON TPESPECIE.CodTpEspecie = INSTRUMENTOS.CodTpEspecie  LEFT JOIN SECTORESINV ON SECTORESINV.CodSectorInv = ESPECIES.CodSectorInv WHERE  ((DATEDIFF (day, '$fechaDesde', INSTRUMENTOS.FVencimiento) >= 0 OR INSTRUMENTOS.FVencimiento IS NULL)) AND ((UPPER(INSTRUMENTOS.Abreviatura) LIKE UPPER('$especie') ))
        INSERT #RPT_POSTIT_COMITENTES (CodComitente ,Descripcion, NumComitente ,CodOficial , Oficial, Administrador, EMail, Telefono, TpComitente, CanalVta, DescCategoria, CmtBloqueado )  select  COMITENTES.CodComitente , COMITENTES.Descripcion, COMITENTES.NumComitente ,OFICIAL.CodOperativo , OFICIAL.Apellido + ', ' + OFICIAL.Nombre, ADCARTERA.Apellido + ', ' + ADCARTERA.Nombre, LEFT(COMITENTES.EmailsInfo,255) , ISNULL(dbo.fnDomicilioEnt(0, COMITENTES.CodComitente, 'DO', 3), ISNULL(dbo.fnDomicilioEnt(0, COMITENTES.CodComitente, 'EC', 3), dbo.fnDomicilioEnt(0, COMITENTES.CodComitente, 'PA', 3))), TPCOMITENTE.Descripcion, CANALESVTA.Descripcion, CATEGORIAS.Descripcion,dbo.fnCmtBloqueado(COMITENTES.CodComitente) CmtBloqueado  FROM  COMITENTES  LEFT JOIN SUCURSALES ON COMITENTES.CodSucursal = SUCURSALES.CodSucursal LEFT JOIN OPERATIVOSROLCMT OFICIALROL INNER JOIN OPERATIVOS OFICIAL ON OFICIAL.CodOperativo = OFICIALROL.CodOperativo AND OFICIALROL.CodRol = 'OC'  ON OFICIALROL.CodComitente = COMITENTES.CodComitente  LEFT JOIN OPERATIVOSROLCMT ADCARTERAROL INNER JOIN OPERATIVOS ADCARTERA ON ADCARTERA.CodOperativo = ADCARTERAROL.CodOperativo AND ADCARTERAROL.CodRol = 'AC'  ON ADCARTERAROL.CodComitente = COMITENTES.CodComitente  LEFT JOIN TPCOMITENTE ON COMITENTES.CodTpComitente = TPCOMITENTE.CodTpComitente  LEFT JOIN CANALESVTA ON COMITENTES.CodCanalVta = CANALESVTA.CodCanalVta  LEFT JOIN CARTERASADMINCMTHIST     INNER JOIN CARTERASADMIN ON CARTERASADMIN.CodCarteraAdmin = CARTERASADMINCMTHIST.CodCarteraAdmin ON CARTERASADMINCMTHIST.CodComitente = COMITENTES.CodComitente AND (('$fechaDesde' >= CARTERASADMINCMTHIST.FechaIngreso AND FechaEgreso IS NULL ) OR '$fechaDesde' BETWEEN CARTERASADMINCMTHIST.FechaIngreso AND DATEADD(day,-1, CARTERASADMINCMTHIST.FechaEgreso)) LEFT JOIN CATEGORIAS ON CATEGORIAS.CodCategoria = COMITENTES.CodCategoria  WHERE ((COMITENTES.NumComitente = $comitente))
        INSERT #RPT_AGENTESDEPO (CodAgenteDepo, NumCuenta, CodDepositario, Descripcion)  select AGENTESDEPO.CodAgenteDepo, AGENTESDEPO.NumCuenta, AGENTESDEPO.CodDepositario, DEPOSITARIOS.Descripcion FROM DEPOSITARIOS INNER JOIN AGENTESDEPO ON AGENTESDEPO.CodDepositario = DEPOSITARIOS.CodDepositario WHERE ((UPPER(DEPOSITARIOS.Descripcion) LIKE UPPER('CAJA DE VALORES') ))
        INSERT #POSTITMERCADOS (CodMercado, Descripcion)  select CodMercado, Descripcion FROM MERCADOS
        exec spINFO_PosicionTitCmt_Precios @FechaHasta='$fechaHasta'
        exec spINFO_PosicionTitCmt_Insert @EsPorConcertacion=0,@FechaHasta='$fechaHasta',@EsPorMercado=0,@EsConDepositario=-1
        exec sp_appCENTCONFIGURACIONUSER @CodAccion='ENTCONFIGUSERd',@CodUsuario=35,@CodEntidad=161
        select ConLineasH, ConLineasV, NumeraHojas FROM appENTCONFIGURACIONUSER WHERE CodEntidad = 161 AND CodUsuario = 35 AND CodEntConfiguracionUser = 'PRUEBA ROFEX OLI'
        select COALESCE(EsImpresionHoriz,-1) EsImpresionHoriz  FROM appENTIDADES WHERE CodEntidad = 161
        select SentenciaSQL, COALESCE(PermiteModificarCol,-1) PermiteModificarCol, COALESCE(PermiteModificarOrd,-1) PermiteModificarOrd, COALESCE(PermiteModificarFn,-1) PermiteModificarFn, COALESCE(PermiteModificarCrt,-1) PermiteModificarCrt, COALESCE(FntColSize,8) FntColSize, COALESCE(FntColName,'Arial') FntColName, COALESCE(FntEncName,'Arial') FntEncName, COALESCE(FntEncSize,8) FntEncSize , COALESCE(FntEncBold,-1) FntEncBold, EsMultiLenguaje FROM appENTIDADES WHERE CodEntidad = 161
        exec sp_appCENTCOLUMNASUSER @CodEntConfiguracionUser='PRUEBA ROFEX OLI',@CodUsuario=35,@CodEntidad=161
        exec sp_appCENTCORTESUSER @CodEntConfiguracionUser='PRUEBA ROFEX OLI',@CodUsuario=35,@CodEntidad=161
        exec sp_appCENTFUNCIONESUSER @CodEntConfiguracionUser='PRUEBA ROFEX OLI',@CodUsuario=35,@CodEntidad=161

        IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE id = object_id('tempdb..#GRILLA_$tableId')) DROP TABLE #GRILLA_$tableId
        CREATE TABLE #GRILLA_$tableId(FMT_GRILLA varchar(80) COLLATE database_default  NULL , DepositarioCuenta varchar(30) COLLATE database_default  NULL , Depositario varchar(80) COLLATE database_default  NULL , NumComitente numeric(15,0) NULL , Comitente varchar(80) COLLATE database_default  NULL , Oficial varchar(300) COLLATE database_default  NULL , CodigoInstrumento numeric(15,0) NULL , AbrevInstrumento varchar(30) COLLATE database_default  NULL , Cantidad numeric(22,10) NULL , ImporteMonPais numeric(19,2) NULL , PorcVResidual numeric(18,12) NULL , Administrador varchar(300) COLLATE database_default  NULL , CantidadNoDisponible numeric(22,10) NULL , CantidadTotal numeric(22,10) NULL , CantidadVDolar numeric(19,2) NULL , ImporteMonPaisND numeric(19,2) NULL , ImporteMonPaisTotal numeric(19,2) NULL , CantidadVR numeric(22,10) NULL , CodComitente numeric(10,0) NULL , CanalVta varchar(80) COLLATE database_default  NULL , DescCategoria varchar(80) COLLATE database_default  NULL , Contacto numeric(10,0) NULL , CUIT varchar(14) COLLATE database_default  NULL , CmtEMail varchar(4000) COLLATE database_default  NULL , CmtBloqueado smallint NULL , Estado varchar(30) COLLATE database_default  NULL , CmtTelefono varchar(50) COLLATE database_default  NULL , TpComitente varchar(80) COLLATE database_default  NULL , Precio numeric(19,10) NULL , CotizaDolar numeric(19,10) NULL , PrecioMonPais numeric(19,10) NULL , CodAgenteDepo numeric(3,0) NULL , FechaPPP smalldatetime NULL , FechaHasta smalldatetime NULL , FechaUltCoti smalldatetime NULL , FXRate numeric(19,10) NULL , Importe numeric(19,2) NULL , CodInstrumento varchar(25) COLLATE database_default  NULL , Cusip varchar(30) COLLATE database_default  NULL , Instrumento varchar(150) COLLATE database_default  NULL , FechaVencimiento smalldatetime NULL , InternalCode varchar(30) COLLATE database_default  NULL , AbrevEspIsin varchar(30) COLLATE database_default  NULL , CodMercado numeric(5,0) NULL , DescMercado varchar(80) COLLATE database_default  NULL , NumCmtMercado numeric(12,0) NULL , Moneda varchar(80) COLLATE database_default  NULL , CodMoneda numeric(5,0) NULL , CodOficial numeric(10,0) NULL , PPP numeric(19,10) NULL , DescSectorInv varchar(80) COLLATE database_default  NULL , TpEspecie varchar(150) COLLATE database_default  NULL , CodTpEspecie numeric(5,0) NULL , TpInstrumento varchar(80) COLLATE database_default  NULL , CodTpInstrumento varchar(2) COLLATE database_default  NULL , PrecioCieDol numeric(19,10) NULL , VMercadoDol numeric(19,2) NULL , Teorica varchar(30) COLLATE database_default  NULL , IDPosicion numeric(10,0) IDENTITY(1, 1) NOT NULL , DFPaginacion numeric(10,0) NULL  PRIMARY KEY (IDPosicion))
        INSERT #GRILLA_$tableId  select 'Posición de Títulos de Comitentes', #POSICIONTITCMT.DepositarioCuenta, #POSICIONTITCMT.Depositario, #POSICIONTITCMT.NumComitente, #POSICIONTITCMT.Comitente, #POSICIONTITCMT.Oficial, #POSICIONTITCMT.CodigoInstrumento, #POSICIONTITCMT.AbrevInstrumento, #POSICIONTITCMT.Cantidad, #POSICIONTITCMT.ImporteMonPais, #POSICIONTITCMT.PorcValorResidual, #POSICIONTITCMT.Administrador, #POSICIONTITCMT.CantidadNoDisponible, #POSICIONTITCMT.CantidadTotal, #POSICIONTITCMT.ImporteMonDolar, #POSICIONTITCMT.ImporteMonPaisND, #POSICIONTITCMT.ImporteMonPaisTotal, #POSICIONTITCMT.CantidadVR, #POSICIONTITCMT.CodComitente, #POSICIONTITCMT.CanalVta, #POSICIONTITCMT.DescCategoria, #POSICIONTITCMT.CodComitente, #POSICIONTITCMT.CUIT, REPLACE(EMail, CHAR(13) + CHAR(10),' '), #POSICIONTITCMT.CmtBloqueado, CASE WHEN EstaAnulado = -1 THEN 'Anulado' ELSE 'Activo' END, #POSICIONTITCMT.Telefono, #POSICIONTITCMT.TpComitente, #POSICIONTITCMT.Precio, #POSICIONTITCMT.PrecioMonDolar, #POSICIONTITCMT.PrecioMonPais, #POSICIONTITCMT.CodAgenteDepo, #POSICIONTITCMT.FechaPPP, #POSICIONTITCMT.FechaHasta, #POSICIONTITCMT.FechaUltCoti, (select dbo.fnCotizacionMon(FechaHasta, CodMoneda, dbo.fnTraerMonedaPaisApl()) Cotizacion from MONEDAS Where CodIDMoneda = 'DO'), #POSICIONTITCMT.Importe, #POSICIONTITCMT.CodInstrumento, #POSICIONTITCMT.Cusip, #POSICIONTITCMT.Instrumento, #POSICIONTITCMT.FechaVencimiento, #POSICIONTITCMT.InternalCode, #POSICIONTITCMT.AbrevEspIsin, #POSICIONTITCMT.CodMercado, #POSICIONTITCMT.Mercado, #POSICIONTITCMT.NumCmtMercado, #POSICIONTITCMT.Moneda, #POSICIONTITCMT.CodMoneda, #POSICIONTITCMT.CodOficial, #POSICIONTITCMT.PPP, #POSICIONTITCMT.DescSectorInv, #POSICIONTITCMT.TpEspecie, #POSICIONTITCMT.CodTpEspecie, #POSICIONTITCMT.TpInstrumento, #POSICIONTITCMT.CodTpInstrumento, #POSICIONTITCMT.PrecioCieDol, #POSICIONTITCMT.VMercadoDol, #POSICIONTITCMT.Teorica,  0 FROM #POSICIONTITCMT LEFT JOIN CMTJURIDICOS ON #POSICIONTITCMT.CodComitente = CMTJURIDICOS.CodComitente
        select Count(*) as Cantidad FROM #GRILLA_$tableId
        UPDATE #GRILLA_$tableId SET DFPaginacion = 0
        SELECT TOP 1 COUNT(IDPosicion) CantidadReg FROM #GRILLA_$tableId GROUP BY #GRILLA_$tableId.FMT_GRILLA,#GRILLA_$tableId.CodAgenteDepo,#GRILLA_$tableId.DepositarioCuenta,#GRILLA_$tableId.Depositario
        select DISTINCT #GRILLA_$tableId.FMT_GRILLA,#GRILLA_$tableId.CodAgenteDepo,#GRILLA_$tableId.DepositarioCuenta,#GRILLA_$tableId.Depositario FROM #GRILLA_$tableId ORDER BY  #GRILLA_$tableId.FMT_GRILLA ASC, #GRILLA_$tableId.CodAgenteDepo ASC
        select MONEDAS.CodMoneda FROM MONEDAS INNER JOIN PAISES     INNER JOIN AGENTES        INNER JOIN  PARAMETROSREL ON AGENTES.CodAgente = PARAMETROSREL.CodAgente     ON AGENTES.CodPais = PAISES.CodPais  ON PAISES.CodMoneda = MONEDAS.CodMoneda 
        select SUM(Cantidad) as K_1 ,SUM(CantidadNoDisponible) as K_2 ,COUNT(NumComitente) as K_3  FROM #GRILLA_$tableId WHERE FMT_GRILLA = 'Posición de Títulos de Comitentes' AND CodAgenteDepo = 2
        select NumComitente,Comitente,Oficial,CodigoInstrumento,AbrevInstrumento,Cantidad,ImporteMonPais,IDPosicion FROM #GRILLA_$tableId WHERE FMT_GRILLA = 'Posición de Títulos de Comitentes' AND CodAgenteDepo = 2 ORDER BY NumComitente ASC,ImporteMonPais DESC

            ";
        file_put_contents('/var/www/ordenes/test1.sql', $sql);
        $instrucciones = explode("\n", $sql);
        $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
        foreach ($instrucciones as $instruccion){
            try {
                $this->dbh->exec($instruccion);
            } catch (PDOException $e){
                echo $e->getMessage();
                die();
            }
        }

        $sql = "SELECT Cantidad FROM #GRILLA_$tableId";        
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);
        unset($stmt);
        if($results == false){
            return $results;
        }else{
            return $this->utf8_converter($results);
        }  
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
