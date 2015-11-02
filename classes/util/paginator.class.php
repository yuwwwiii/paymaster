<?php

/**
 * paginator.class.php
 * misc utilities
 *
 * $Id$
 *
 * @author aljo fabro <acfabro@gmail.com>
 * @package extension
 */

/**
 * default records per page
 */
define('PAGINATOR_RECSPERPAGE',1000);

/**
 * default maximum pages per page browser line
 */
define('PAGINATOR_LINEPAGES',10);

/**
 * class Paginator
 * used to split record sets into pages
 */
class Paginator {
    
    /**
     * Current page number
     * @var int 
     */
    var $pagenum;
    
    /**
     * Total number record rows
     * @var int 
     */
    var $numrecords;
    
    /**
     * Number of pages in browse line
     * @var int
     */
    var $linepages;

    /**
     * Records per page
     * @var int
     */
    var $recsperpage;
    
    /**
     * Link to page, with @@ as page num. @@ will be replaced with the pagenum at runtime e.g. 'profiles-list.php?p=@@'
     * @var string
     */
    var $linkPage;
    
    /**
     * Global link attributes
     * @var string
     */
    var $linkAttribs; 
    
    /**
     * constructor
     */
    function Paginator() {
        $this->pagenum = 0;    
        $this->numrecords = 0;    
        $this->recsperpage = PAGINATOR_RECSPERPAGE;
        $this->linepages = PAGINATOR_LINEPAGES;
        $this->linkPage = 0;    
    }

    /**
     * gets the number of pages
     * @return int number of pages
     */
    function numberOfPages() {
        if ( !$this->numrecords ) return 1;
        if ( !$this->recsperpage ) return 1;
        return ceil( $this->numrecords/$this->recsperpage );
    }
    
    /**
     * gets the next page num
     * @return int the next page number
     */
    function nextPage() {
        if ( $this->pagenum >= $this->numberOfPages() ) return $this->numberOfPages();
        return $this->pagenum+1;
    }
    
    /**
     * gets the prev page num
     * @return int the prev page number
     */
    function prevPage() {
        if ( $this->pagenum <= 1 ) return 1;
        return $this->pagenum-1;
    }
    
    /**
     * Gets the First page link
     * @param string $name Literal Name of link. you may put in html to use an image as link
     * @param string $arrow Text representation of an arrow. eg. &lt;&lt; for <<First
     * @param string $attribs In plain html, additional attriutes of the <a href> tag
     */
    function linkFirst( $name='First', $arrow='&#171;', $attribs='' ) {
        $attribs = $attribs? $attribs: $this->linkAttribs;

        // replace @@ in link to page with pagenum
        $myHref = str_replace('@@',1,$this->linkPage);
        $myLink = ($this->pagenum!=1)?
                  "$arrow<a href=\"$myHref\" $attribs>$name</a>":
                  $arrow.$name;
        return $myLink;
    }

    /**
     * Gets the Last page link
     * @param string $name Literal Name of link. you may put in html to use an image as link
     * @param string $arrow Text representation of an arrow. eg. &gt;&gt; for Last>>
     * @param string $attribs In plain html, additional attriutes of the <a href> tag
     */
    function linkLast( $name='Last', $arrow='&#187;', $attribs='' ) {
        $attribs = $attribs? $attribs: $this->linkAttribs;

        // replace @@ in link to page with pagenum
        $myHref = str_replace('@@',$this->numberOfPages(),$this->linkPage);
        $myLink = ($this->pagenum!=$this->numberOfPages())?
                  "<a href=\"$myHref\" $attribs>$name</a>$arrow":
                  $name.$arrow;
        return $myLink;
    }

    /**
     * Gets the Prev page link
     * @param string $name Literal Name of link. you may put in html to use an image as link
     * @param string $arrow Text representation of an arrow. eg. &lt; for <Prev
     * @param string $attribs In plain html, additional attriutes of the <a href> tag
     */
    function linkPrev( $name='Prev', $arrow='&lt;', $attribs='' ) {
        $attribs = $attribs? $attribs: $this->linkAttribs;

        // replace @@ in link to page with pagenum
        $myHref = str_replace('@@',$this->prevPage(),$this->linkPage);
        $myLink = ($this->pagenum!=$this->prevPage())?
                  "$arrow<a href=\"$myHref\" $attribs>$name</a>":
                  $arrow.$name;
        return $myLink;
    }

    /**
     * Gets the Next page link
     * @param string $name Literal Name of link. you may put in html to use an image as link
     * @param string $arrow Text representation of an arrow. eg. &gt; for Next>
     * @param string $attribs In plain html, additional attriutes of the <a href> tag
     */
    function linkNext( $name='Next', $arrow='&gt;', $attribs='' ) {
        $attribs = $attribs? $attribs: $this->linkAttribs;

        // replace @@ in link to page with pagenum
        $myHref = str_replace('@@',$this->nextPage(),$this->linkPage);
        $myLink = ($this->pagenum!=$this->nextPage())?
                  "<a href=\"$myHref\" $attribs>$name</a>$arrow":
                  $name.$arrow;
        return $myLink;
    }
    
    /**
     * Gets the page number link
     * @param string $pagenum Page number
     * @param string $name Literal Name of link. you may put in html to use an image as link
     * @param string $attribs In plain html, additional attriutes of the <a href> tag
     */
    function linkPageNum( $pagenum, $name, $attribs='' ) {
        $attribs = $attribs? $attribs: $this->linkAttribs;

        // replace @@ in link to page with pagenum
        $myHref = str_replace('@@',$pagenum,$this->linkPage);
        $myLink = ($this->pagenum!=$pagenum)?
                  "<a href=\"$myHref\" $attribs>$name</a>":
                  $name;
        return $myLink;
    }
    
    /**
     * Sets global/default link attributes
     *
     * @param string $attribs Attributes for &lt;a href=""&gt; link
     */
    function setLinkAttribs( $attribs ) {
    	$this->linkAttribs = $attribs;	
    }

    /**
     * Gets a typical paginator line
     * the default format of the paginator is: <<First | Prev | 1 | 2 | 3 | 4 |...| n | Next | Last>> 
     * @param string $attribs html attributes of <a href> tags
     */
    function getPaginatorLine($attribs='') {
        // assemble pages line
        $pageFirst = ($this->pagenum-(round($this->linepages/2))<=1)?1:
                      $this->pagenum-(round($this->linepages/2));
        $pageLast = ($this->pagenum+(round($this->linepages/2))>$this->numberOfPages())?
                     $this->numberOfPages():$this->pagenum+(round($this->linepages/2))-1;
                     
        if ( $pageLast-($pageFirst-1) < $this->linepages ) {
            if ( $this->pagenum-round($this->linepages/2) < $pageFirst ) {
                // adjust right
                $adjRight = (1+($this->linepages/2)-$this->pagenum);
                $pageLast = ($pageLast+$adjRight)>=$this->numberOfPages()?
                            $this->numberOfPages():$pageLast+$adjRight;
            } else 
            
            if ( $this->pagenum+round($this->linepages/2)-1 > $pageLast ) {
                // adjust left
                $adjLeft = (($this->linepages/2)-1-($pageLast-$this->pagenum));
                $pageFirst = ($pageFirst-$adjLeft)<=1?
                             1:($pageFirst-$adjLeft);
            }
               
        }
        
        $myPages = array();
        for ( $ctr=$pageFirst; $ctr<=$pageLast; $ctr++ ) {
            $myPages[] = $this->linkPageNum($ctr,$ctr,$attribs);
        }
        
        if ( !$this->numrecords ) {
        	$myPages = array('1');	
        }
       
        // assemble the rest of the page line
        $myLine = "Page {$this->pagenum}/".number_format($this->numberOfPages(),0,".",",")." of ".number_format($this->numrecords,0,".",",")." | ";
        $myLine .= $this->linkFirst('First',' &#171; ',$attribs);
        $myLine .= $this->linkPrev('Prev',' &bull; ',$attribs);
        $myLine .= ' &bull; ';
        $myLine .= implode(' ',$myPages);
        $myLine .= ' &bull; ';
        $myLine .= $this->linkNext('Next',' &bull; ',$attribs);
        $myLine .= $this->linkLast('Last',' &#187; ',$attribs);
        
        return $myLine;
    }
}

?>