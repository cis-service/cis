<?php
/*
 * Central Image Service - Client
 * 
 * Copyright 2013 Lukas Plattner <voxel@cube>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

namespace Cis;

class Client
{
    /**
     * Domain/Ip of Masterserver
     * @var string
     */
    protected $masterDomain = '127.0.0.1';
    /**
     * Domain/Ip of Slaveserver
     * @var string
     */
    protected $slaveDomain = null;
    /**
     * User to Connect to cis
     * @var string
     */
    protected $user=null;
    /**
     * Password to connect to cis
     * @var string
     */
    protected $password=null;
    /**
     * Constructor of class Client.
     * 
     * @param array config Array with configurationoptions
     * @return void
     */
    public function __construct($config=array())
    {
        if(is_array($config))
        {
            if(array_key_exists("masterDomain",$config))
                $this->masterDomain = $config["masterDomain"];
            if(array_key_exists("slaveDomain",$config))
                $this->masterDomain = $config["slaveDomain"];
        }
    }
    /**
     * Saves Image to CIS from local Path
     * @param string path Path to image
     * @param string title Titel of the image
     * @param string credit Credit of the image
     * @throws Exception
     */
    public function saveFromPath($path,$title, $credit,$id=false)
    {
        if(!file_exists($path))
            throw new Exception("File not exitsts");
        
        $params=array('title'=>$title,'credit'=>$credit,'file_contents'=>'@'.realpath($path));
        $id=intval($id);
        $c = $this->getCurl("/cis/api/set".($id?"/$id":""),$params,true);
        curl_setopt($c, CURLINFO_HEADER_OUT, 1); 
        $return = curl_exec($c);
        var_dump($return);echo "<br>\n";echo "<br>\n";
        var_dump(curl_getinfo($c));echo "<br>\n";echo "<br>\n";
        echo curl_getinfo($c, CURLINFO_HEADER_OUT);
        $return = json_decode($return);
        if($return->success == true)
        {
            return $return;
        }
        echo "<br>\n";echo "<br>\n";
        return $return;
    }
    
    public function get($id,$dimension="x")
    {
        $id = intval($id);
        $dim = explode('x',$dimension);
        if(count($dim)!=2)
        {
            throw new \Exception("Incorrect dimension");
        }
        $dim[0] = intval($dim[0]);
        $dim[1] = intval($dim[1]);
        $dim = implode('x',$dim);
        $c = $this->getCurl("/cis/api/set"."/$id"."/$dim");
        $return = curl_exec($c);
        return $return;
    }
    
    protected function getCurl($url,$data=array(), $post = false)
    {
        $c = \curl_init();
        $options = array(
            CURLOPT_RETURNTRANSFER=>true, 
            CURLOPT_USERAGENT=>'CIS-Client',
            CURLOPT_URL=>'http://'.$this->masterDomain.$url,
        );
        if($post)
        {
            $options[CURLOPT_POST]= true;
            $options[CURLOPT_POSTFIELDS] = $data;
        }
        if($this->user)
        {
            $options[CURLOPT_USERPWD]=$this->user;
            if($this->password)
                $options[CURLOPT_USERPWD] .= ":".$this->password;
        }
        curl_setopt_array($c, $options);
        return $c;
    }
    public function setUser($user)
    {
        $this->user=$user;
        return $this;
    }
    public function setPassword($pw)
    {
        $this->password=$pw;
        return $this;
    }
    public function auth()
    {
        $c = $this->getCurl("/cis/api/auth",array('user'=>$this->user,'password'=>$this->password),true);
        $return = curl_exec($c);
        return json_decode($return);
    }
}
