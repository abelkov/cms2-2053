<?xml version="1.0" encoding="utf-8"?>

<!-- A custom stream works fine in the main file -->
<!DOCTYPE xsl:stylesheet SYSTEM "custom://localhost/entities.dtd">

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">

        <!-- Both entities correctly resolve to the string "test" -->
        1 &test;
        <xsl:text>2 &test;</xsl:text>

        <xsl:call-template name="include" />
    </xsl:template>

    <xsl:include href="include.xsl" />
</xsl:stylesheet>
