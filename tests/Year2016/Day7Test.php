<?php

namespace Tests\Year2016;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Day7Test extends TestCase
{
    #[Test]
    public function it_can_check_if_it_supports_tls(): void
    {
        $this->assertTrue($this->supportsTLS('abba[mnop]qrst'));
        $this->assertFalse($this->supportsTLS('abcd[bddb]xyyx'));
        $this->assertFalse($this->supportsTLS('aaaa[qwer]tyui'));
        $this->assertTrue($this->supportsTLS('ioxxoj[asdfgh]zxcvbn'));
        $this->assertFalse($this->supportsTLS('dkszjaqhbkyxwvwj[wykmzcvppharrax]pvrqzqhabqtuhrj[qfxnormttaxsldn]kxjwkrjwvbiutgnfnw[mbspfflblosguqzt]nifozavfsfkngds'));
        $this->assertFalse($this->supportsTLS('xmurhwryxntmdwwv[bveccegjmwnppgio]rbnnbsqzutgyoign'));
        $this->assertTrue($this->supportsTLS('aieqqwhwfwxpldni[huyxdcvpglqqkeyeia]teztoyeycqohfli[uqvfjzbsvttphqxsy]afsxoqbqvtxtgriuzjm[psumrbbtxdxlwpk]qwpuwevhjellemzp'));
        $this->assertTrue($this->supportsTLS('pnevxgcsnqcdezwf[mnftbwqkrfmwcgp]rkmeslzoffovqheq[vxdcnglifignqqx]xiofsoyrslgyfrxl'));
        $this->assertTrue($this->supportsTLS('nnmyoxtukxhrsgt[ecovrntpmkcaekonw]ncfzdxdlawbwtxqpkik[fkkkkxidubuatpihcnc]wqxmtvyakouvijt[tjvyhgempiufanh]bcibhdmbmbmmbyyi'));
    }

    #[Test]
    public function it_can_check_if_it_supports_ssl(): void
    {
        $this->assertTrue($this->supportsSSL('aba[bab]xyz'));
        $this->assertFalse($this->supportsSSL('xyx[xyx]xyx'));
        $this->assertTrue($this->supportsSSL('aaa[kek]eke'));
        $this->assertTrue($this->supportsSSL('izazbz[bzb]cdb'));
    }

    #[Test]
    public function it_can_solve_day_7a(): void
    {
        $result = $this->lines()
            ->filter(fn (string $line) => $this->supportsTLS($line))
            ->count();

        $this->assertEquals(118, $result);
    }

    #[Test]
    public function it_can_solve_day_7b(): void
    {
        $result = $this->lines()
            ->filter(fn (string $line) => $this->supportsSSL($line))
            ->count();

        $this->assertEquals(260, $result);
    }

    private function supportsTLS(string $address): bool
    {
        preg_match_all('/\[([a-z]+)\]/m', $address, $matches);
        $outside = explode(',', preg_replace('/\[[a-z]+\]/m', ',', $address));
        $inside = $matches[1];

        foreach ($inside as $i) {
            $count = preg_match_all('/([a-z])([a-z])\2\1/m', $i, $m);

            if ($count > 0) {
                foreach ($m[0] as $match) {
                    if ($match[0] !== $match[1]) {
                        return false;
                    }
                }
            }
        }
        foreach ($outside as $i) {
            $count = preg_match_all('/([a-z])([a-z])\2\1/m', $i, $m);

            if ($count > 0) {
                foreach ($m[0] as $match) {
                    if ($match[0] !== $match[1]) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function supportsSSL(string $address): bool
    {
        preg_match_all('/\[([a-z]+)\]/m', $address, $matches);
        $outside = explode(',', preg_replace('/\[[a-z]+\]/m', ',', $address));
        $inside = $matches[1];
        $outsideABA = [];
        foreach ($outside as $i) {
            $count = preg_match_all('/(?=((\w)(\w)(\2)))/m', $i, $m);

            if ($count > 0) {
                foreach ($m[1] as $match) {
                    if ($match[0] !== $match[1]) {
                        $outsideABA[] = $match;
                    }
                }
            }
        }

        $search = array_map(fn (string $match) => $match[1].$match[0].$match[1], $outsideABA);

        foreach ($inside as $i) {
            if (Str::contains($i, $search)) {
                return true;
            }
        }

        return false;
    }
}
