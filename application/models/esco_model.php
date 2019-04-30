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
                default:
                    exit('The application environment is not set correctly.');
            }
        }
    }
    
    private $dbh;
    public $numComitente;
    public $instrumento;
    public $fecha;
    
    public function getComitente(){
        $sql = "select  c.Descripcion comitente,
                        c.EsFisico esFisico,
                        o.Apellido + ', ' + o.Nombre as oficial,
                        ISNULL(jur.CUIT, isnull(p.CUIL, p.CUIT)) as cuit
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
                where   c.NumComitente   = {$this->numComitente}
                and     con.CodTpCondominio = 'TI'
                and     con.EstaAnulado     = 0
                and     c.EstaAnulado       = 0
                order by con.Posicion";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);
        unset($stmt);
        return $this->utf8_converter($results);
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
}
